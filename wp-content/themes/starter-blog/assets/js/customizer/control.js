(function(api) {
	// Extends our custom "example-1" section.
	api.sectionConstructor["starterblog-pro"] = api.Section.extend({
		// No events for this type of section.
		attachEvents: function() {},

		// Always make the section active.
		isContextuallyActive: function() {
			return true;
		}
	});
})(wp.customize);

(function($) {
	var api = wp.customize;

	api.bind("pane-contents-reflowed", function() {
		// Reflow sections
		var sections = [];
		api.section.each(function(section) {
			if (
				"starterblog_section" !== section.params.type ||
				"undefined" === typeof section.params.section
			) {
				return;
			}

			sections.push(section);
		});

		sections.sort(api.utils.prioritySort).reverse();
		$.each(sections, function(i, section) {
			var parentContainer = $(
				"#sub-accordion-section-" + section.params.section
			);
			parentContainer
				.children(".section-meta")
				.after(section.headContainer);
		});

		// Reflow panels
		var panels = [];
		api.panel.each(function(panel) {
			if (
				"starterblog_panel" !== panel.params.type ||
				"undefined" === typeof panel.params.panel
			) {
				return;
			}

			panels.push(panel);
		});
		panels.sort(api.utils.prioritySort).reverse();
		$.each(panels, function(i, panel) {
			var parentContainer = $(
				"#sub-accordion-panel-" + panel.params.panel
			);
			parentContainer.children(".panel-meta").after(panel.headContainer);
		});
	});

	// Extend Panel
	var _panelEmbed = wp.customize.Panel.prototype.embed;
	var _panelIsContextuallyActive =
		wp.customize.Panel.prototype.isContextuallyActive;
	var _panelAttachEvents = wp.customize.Panel.prototype.attachEvents;

	wp.customize.Panel = wp.customize.Panel.extend({
		attachEvents: function() {
			if (
				"starterblog_panel" !== this.params.type ||
				"undefined" === typeof this.params.panel
			) {
				_panelAttachEvents.call(this);
				return;
			}

			_panelAttachEvents.call(this);
			var panel = this;
			panel.expanded.bind(function(expanded) {
				var parent = api.panel(panel.params.panel);
				if (expanded) {
					parent.contentContainer.addClass("current-panel-parent");
				} else {
					parent.contentContainer.removeClass("current-panel-parent");
				}
			});

			panel.container
				.find(".customize-panel-back")
				.off("click keydown")
				.on("click keydown", function(event) {
					if (api.utils.isKeydownButNotEnterEvent(event)) {
						return;
					}
					event.preventDefault(); // Keep this AFTER the key filter above
					if (panel.expanded()) {
						api.panel(panel.params.panel).expand();
					}
				});
		},
		embed: function() {
			if (
				"starterblog_panel" !== this.params.type ||
				"undefined" === typeof this.params.panel
			) {
				_panelEmbed.call(this);
				return;
			}
			_panelEmbed.call(this);
			var panel = this;
			var parentContainer = $(
				"#sub-accordion-panel-" + this.params.panel
			);
			parentContainer.append(panel.headContainer);
		},
		isContextuallyActive: function() {
			if ("starterblog_panel" !== this.params.type) {
				return _panelIsContextuallyActive.call(this);
			}

			var panel = this;
			var children = this._children("panel", "section");
			api.panel.each(function(child) {
				if (!child.params.panel) {
					return;
				}
				if (child.params.panel !== panel.id) {
					return;
				}
				children.push(child);
			});

			children.sort(api.utils.prioritySort);
			var activeCount = 0;
			_(children).each(function(child) {
				if (child.active() && child.isContextuallyActive()) {
					activeCount += 1;
				}
			});
			return activeCount !== 0;
		}
	});

	// Extend Section
	var _sectionEmbed = wp.customize.Section.prototype.embed;
	var _sectionIsContextuallyActive =
		wp.customize.Section.prototype.isContextuallyActive;
	var _sectionAttachEvents = wp.customize.Section.prototype.attachEvents;

	wp.customize.Section = wp.customize.Section.extend({
		attachEvents: function() {
			if (
				"starterblog_section" !== this.params.type ||
				"undefined" === typeof this.params.section
			) {
				_sectionAttachEvents.call(this);
				return;
			}
			_sectionAttachEvents.call(this);
			var section = this;
			section.expanded.bind(function(expanded) {
				var parent = api.section(section.params.section);
				if (expanded) {
					parent.contentContainer.addClass("current-section-parent");
				} else {
					parent.contentContainer.removeClass(
						"current-section-parent"
					);
				}
			});

			section.container
				.find(".customize-section-back")
				.off("click keydown")
				.on("click keydown", function(event) {
					if (api.utils.isKeydownButNotEnterEvent(event)) {
						return;
					}
					event.preventDefault(); // Keep this AFTER the key filter above
					if (section.expanded()) {
						api.section(section.params.section).expand();
					}
				});
		},
		embed: function() {
			if (
				"starterblog_section" !== this.params.type ||
				"undefined" === typeof this.params.section
			) {
				_sectionEmbed.call(this);
				return;
			}

			_sectionEmbed.call(this);
			var section = this;
			var parentContainer = $(
				"#sub-accordion-section-" + this.params.section
			);
			parentContainer.append(section.headContainer);
		},
		isContextuallyActive: function() {
			if ("starterblog_section" !== this.params.type) {
				return _sectionIsContextuallyActive.call(this);
			}

			var section = this;
			var children = this._children("section", "control");
			api.section.each(function(child) {
				if (!child.params.section) {
					return;
				}

				if (child.params.section !== section.id) {
					return;
				}
				children.push(child);
			});

			children.sort(api.utils.prioritySort);
			var activeCount = 0;
			_(children).each(function(child) {
				if ("undefined" !== typeof child.isContextuallyActive) {
					if (child.active() && child.isContextuallyActive()) {
						activeCount += 1;
					}
				} else {
					if (child.active()) {
						activeCount += 1;
					}
				}
			});
			return activeCount !== 0;
		}
	});
})(jQuery);

(function($, wpcustomize) {
	"use strict";

	var $document = $(document);
	var is_rtl = StarterBlog_Control_Args.is_rtl;

	var StarterBlogMedia = {
		setAttachment: function(attachment) {
			this.attachment = attachment;
		},
		addParamsURL: function(url, data) {
			if (!$.isEmptyObject(data)) {
				url += (url.indexOf("?") >= 0 ? "&" : "?") + $.param(data);
			}
			return url;
		},
		getThumb: function(attachment) {
			var control = this;
			if (typeof attachment !== "undefined") {
				this.attachment = attachment;
			}
			var t = new Date().getTime();
			if (typeof this.attachment.sizes !== "undefined") {
				if (typeof this.attachment.sizes.medium !== "undefined") {
					return control.addParamsURL(
						this.attachment.sizes.medium.url,
						{ t: t }
					);
				}
			}
			return control.addParamsURL(this.attachment.url, { t: t });
		},
		getURL: function(attachment) {
			if (typeof attachment !== "undefined") {
				this.attachment = attachment;
			}
			var t = new Date().getTime();
			return this.addParamsURL(this.attachment.url, { t: t });
		},
		getID: function(attachment) {
			if (typeof attachment !== "undefined") {
				this.attachment = attachment;
			}
			return this.attachment.id;
		},
		getInputID: function(attachment) {
			$(".attachment-id", this.preview).val();
		},
		setPreview: function($el) {
			this.preview = $el;
		},
		insertImage: function(attachment) {
			if (typeof attachment !== "undefined") {
				this.attachment = attachment;
			}

			var url = this.getURL();
			var id = this.getID();
			var mime = this.attachment.mime;
			$(".starterblog-image-preview", this.preview)
				.addClass("starterblog--has-file")
				.html('<img src="' + url + '" alt="">');
			$(".attachment-url", this.preview).val(this.toRelativeUrl(url));
			$(".attachment-mime", this.preview).val(mime);
			$(".attachment-id", this.preview)
				.val(id)
				.trigger("change");
			this.preview.addClass("attachment-added");
			this.showChangeBtn();
		},
		toRelativeUrl: function(url) {
			return url;
			//return url.replace( StarterBlog_Control_Args.home_url, '' );
		},
		showChangeBtn: function() {
			$(".starterblog--add", this.preview).addClass("starterblog--hide");
			$(".starterblog--change", this.preview).removeClass(
				"starterblog--hide"
			);
			$(".starterblog--remove", this.preview).removeClass(
				"starterblog--hide"
			);
		},
		insertVideo: function(attachment) {
			if (typeof attachment !== "undefined") {
				this.attachment = attachment;
			}

			var url = this.getURL();
			var id = this.getID();
			var mime = this.attachment.mime;
			var html =
				'<video width="100%" height="" controls><source src="' +
				url +
				'" type="' +
				mime +
				'">Your browser does not support the video tag.</video>';
			$(".starterblog-image-preview", this.preview)
				.addClass("starterblog--has-file")
				.html(html);
			$(".attachment-url", this.preview).val(this.toRelativeUrl(url));
			$(".attachment-mime", this.preview).val(mime);
			$(".attachment-id", this.preview)
				.val(id)
				.trigger("change");
			this.preview.addClass("attachment-added");
			this.showChangeBtn();
		},
		insertFile: function(attachment) {
			if (typeof attachment !== "undefined") {
				this.attachment = attachment;
			}
			var url = attachment.url;
			var mime = this.attachment.mime;
			var basename = url.replace(/^.*[\\\/]/, "");

			$(".starterblog-image-preview", this.preview)
				.addClass("starterblog--has-file")
				.html(
					'<a href="' +
						url +
						'" class="attachment-file" target="_blank">' +
						basename +
						"</a>"
				);
			$(".attachment-url", this.preview).val(this.toRelativeUrl(url));
			$(".attachment-mime", this.preview).val(mime);
			$(".attachment-id", this.preview)
				.val(this.getID())
				.trigger("change");
			this.preview.addClass("attachment-added");
			this.showChangeBtn();
		},
		remove: function($el) {
			if (typeof $el !== "undefined") {
				this.preview = $el;
			}
			$(".starterblog-image-preview", this.preview)
				.removeAttr("style")
				.html("")
				.removeClass("starterblog--has-file");
			$(".attachment-url", this.preview).val("");
			$(".attachment-mime", this.preview).val("");
			$(".attachment-id", this.preview)
				.val("")
				.trigger("change");
			this.preview.removeClass("attachment-added");

			$(".starterblog--add", this.preview).removeClass("starterblog--hide");
			$(".starterblog--change", this.preview).addClass("starterblog--hide");
			$(".starterblog--remove", this.preview).addClass("starterblog--hide");
		}
	};

	StarterBlogMedia.controlMediaImage = wp.media({
		title: wp.media.view.l10n.addMedia,
		multiple: false,
		library: { type: "image" }
	});

	StarterBlogMedia.controlMediaImage.on("select", function() {
		var attachment = StarterBlogMedia.controlMediaImage
			.state()
			.get("selection")
			.first()
			.toJSON();
		StarterBlogMedia.insertImage(attachment);
	});

	StarterBlogMedia.controlMediaVideo = wp.media({
		title: wp.media.view.l10n.addMedia,
		multiple: false,
		library: { type: "video" }
	});

	StarterBlogMedia.controlMediaVideo.on("select", function() {
		var attachment = StarterBlogMedia.controlMediaVideo
			.state()
			.get("selection")
			.first()
			.toJSON();
		StarterBlogMedia.insertVideo(attachment);
	});

	StarterBlogMedia.controlMediaFile = wp.media({
		title: wp.media.view.l10n.addMedia,
		multiple: false
	});

	StarterBlogMedia.controlMediaFile.on("select", function() {
		var attachment = StarterBlogMedia.controlMediaFile
			.state()
			.get("selection")
			.first()
			.toJSON();
		StarterBlogMedia.insertFile(attachment);
	});

	var starterblog_controls_list = {};
	//---------------------------------------------------------------------------

	var starterblogField = {
		devices: ["desktop", "tablet", "mobile"],
		allDevices: ["desktop", "tablet", "mobile"],
		type: "starterblog",
		getTemplate: _.memoize(function() {
			var field = this;
			var compiled,
				/*
				 * Underscore's default ERB-style templates are incompatible with PHP
				 * when asp_tags is enabled, so WordPress uses Mustache-inspired templating syntax.
				 *
				 * @see trac ticket #22344.
				 */
				options = {
					evaluate: /<#([\s\S]+?)#>/g,
					interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
					escape: /\{\{([^\}]+?)\}\}(?!\})/g,
					variable: "data"
				};

			return function(data, id, data_variable_name) {
				if (_.isUndefined(id)) {
					//id = 'tmpl-customize-control-' + field.type;
					id = "tmpl-field-starterblog-" + field.type;
				}
				if (
					!_.isUndefined(data_variable_name) &&
					_.isString(data_variable_name)
				) {
					options.variable = data_variable_name;
				} else {
					options.variable = "data";
				}
				compiled = _.template($("#" + id).html(), null, options);
				return compiled(data);
			};
		}),

		getFieldValue: function(name, fieldSetting, $field) {
			var control = this;
			var type = undefined;
			var support_devices = false;

			if (!_.isUndefined(fieldSetting)) {
				type = fieldSetting.type;
				support_devices = fieldSetting.device_settings;
			}

			var value = "";
			switch (type) {
				case "media":
				case "image":
				case "video":
				case "attachment":
				case "audio":
					if (support_devices) {
						value = {};
						_.each(control.allDevices, function(device) {
							var _name = name + "-" + device;
							value[device] = {
								id: $(
									'input[data-name="' + _name + '"]',
									$field
								).val(),
								url: $(
									'input[data-name="' + _name + '-url"]',
									$field
								).val(),
								mime: $(
									'input[data-name="' + _name + '-mime"]',
									$field
								).val()
							};
						});
					} else {
						value = {
							id: $(
								'input[data-name="' + name + '"]',
								$field
							).val(),
							url: $(
								'input[data-name="' + name + '-url"]',
								$field
							).val(),
							mime: $(
								'input[data-name="' + name + '-mime"]',
								$field
							).val()
						};
					}

					break;
				case "css_ruler":
					if (support_devices) {
						value = {};
						_.each(control.allDevices, function(device) {
							var _name = name + "-" + device;
							value[device] = {
								unit: $(
									'input[data-name="' +
										_name +
										'-unit"]:checked',
									$field
								).val(),
								top: $(
									'input[data-name="' + _name + '-top"]',
									$field
								).val(),
								right: $(
									'input[data-name="' + _name + '-right"]',
									$field
								).val(),
								bottom: $(
									'input[data-name="' + _name + '-bottom"]',
									$field
								).val(),
								left: $(
									'input[data-name="' + _name + '-left"]',
									$field
								).val(),
								link: $(
									'input[data-name="' + _name + '-link"]',
									$field
								).is(":checked")
									? 1
									: ""
							};
						});
					} else {
						value = {
							unit: $(
								'input[data-name="' + name + '-unit"]:checked',
								$field
							).val(),
							top: $(
								'input[data-name="' + name + '-top"]',
								$field
							).val(),
							right: $(
								'input[data-name="' + name + '-right"]',
								$field
							).val(),
							bottom: $(
								'input[data-name="' + name + '-bottom"]',
								$field
							).val(),
							left: $(
								'input[data-name="' + name + '-left"]',
								$field
							).val(),
							link: $(
								'input[data-name="' + name + '-link"]',
								$field
							).is(":checked")
								? 1
								: ""
						};
					}

					break;
				case "shadow":
					if (support_devices) {
						value = {};
						_.each(control.allDevices, function(device) {
							var _name = name + "-" + device;
							value[device] = {
								color: $(
									'input[data-name="' + _name + '-color"]',
									$field
								).val(),
								x: $(
									'input[data-name="' + _name + '-x"]',
									$field
								).val(),
								y: $(
									'input[data-name="' + _name + '-y"]',
									$field
								).val(),
								blur: $(
									'input[data-name="' + _name + '-blur"]',
									$field
								).val(),
								spread: $(
									'input[data-name="' + _name + '-spread"]',
									$field
								).val(),
								inset: $(
									'input[data-name="' + _name + '-inset"]',
									$field
								).is(":checked")
									? 1
									: false
							};
						});
					} else {
						value = {
							color: $(
								'input[data-name="' + name + '-color"]',
								$field
							).val(),
							x: $(
								'input[data-name="' + name + '-x"]',
								$field
							).val(),
							y: $(
								'input[data-name="' + name + '-y"]',
								$field
							).val(),
							blur: $(
								'input[data-name="' + name + '-blur"]',
								$field
							).val(),
							spread: $(
								'input[data-name="' + name + '-spread"]',
								$field
							).val(),
							inset: $(
								'input[data-name="' + name + '-inset"]',
								$field
							).is(":checked")
								? 1
								: false
						};
					}

					break;
				case "font_style":
					if (support_devices) {
						value = {};
						_.each(control.allDevices, function(device) {
							var _name = name + "-" + device;
							value[device] = {
								b: $(
									'input[data-name="' + _name + '-b"]',
									$field
								).is(":checked")
									? 1
									: "",
								i: $(
									'input[data-name="' + _name + '-i"]',
									$field
								).is(":checked")
									? 1
									: "",
								u: $(
									'input[data-name="' + _name + '-u"]',
									$field
								).is(":checked")
									? 1
									: "",
								s: $(
									'input[data-name="' + _name + '-s"]',
									$field
								).is(":checked")
									? 1
									: "",
								t: $(
									'input[data-name="' + _name + '-t"]',
									$field
								).is(":checked")
									? 1
									: ""
							};
						});
					} else {
						value = {
							b: $(
								'input[data-name="' + name + '-b"]',
								$field
							).is(":checked")
								? 1
								: "",
							i: $(
								'input[data-name="' + name + '-i"]',
								$field
							).is(":checked")
								? 1
								: "",
							u: $(
								'input[data-name="' + name + '-u"]',
								$field
							).is(":checked")
								? 1
								: "",
							s: $(
								'input[data-name="' + name + '-s"]',
								$field
							).is(":checked")
								? 1
								: "",
							t: $(
								'input[data-name="' + name + '-t"]',
								$field
							).is(":checked")
								? 1
								: ""
						};
					}

					break;
				case "font":
					if (support_devices) {
						value = {};
						_.each(control.allDevices, function(device) {
							var _name = name + "-" + device;
							var subsets = {};
							$(
								'.list-subsets[data-name="' +
									_name +
									'-subsets"] input',
								$field
							).each(function() {
								if ($(this).is(":checked")) {
									var _v = $(this).val();
									subsets[_v] = _v;
								}
							});
							value[device] = {
								font: $(
									'select[data-name="' + _name + '-font"]',
									$field
								).val(),
								type: $(
									'input[data-name="' + _name + '-type"]',
									$field
								).val(),
								variant: $(
									'select[data-name="' + _name + '-variant"]',
									$field
								).val(), // variant
								subsets: subsets
							};
						});
					} else {
						var subsets = {};
						$(
							'.list-subsets[data-name="' +
								name +
								'-subsets"] input',
							$field
						).each(function() {
							if ($(this).is(":checked")) {
								var _v = $(this).val();
								subsets[_v] = _v;
							}
						});
						value = {
							font: $(
								'select[data-name="' + name + '-font"]',
								$field
							).val(),
							type: $(
								'input[data-name="' + name + '-type"]',
								$field
							).val(),
							variant: $(
								'select[data-name="' + name + '-variant"]',
								$field
							).val(),
							subsets: subsets
						};
					}

					break;
				case "slider":
					if (support_devices) {
						value = {};
						_.each(control.allDevices, function(device) {
							var _name = name + "-" + device;
							value[device] = {
								unit: $(
									'input[data-name="' +
										_name +
										'-unit"]:checked',
									$field
								).val(),
								value: $(
									'input[data-name="' + _name + '-value"]',
									$field
								).val()
							};
						});
					} else {
						value = {
							unit: $(
								'input[data-name="' + name + '-unit"]:checked',
								$field
							).val(),
							value: $(
								'input[data-name="' + name + '-value"]',
								$field
							).val()
						};
					}

					break;
				case "icon":
					if (support_devices) {
						value = {};
						_.each(control.allDevices, function(device) {
							var _name = name + "-" + device;
							value[device] = {
								type: $(
									'input[data-name="' + _name + '-type"]',
									$field
								).val(),
								icon: $(
									'input[data-name="' + _name + '"]',
									$field
								).val()
							};
						});
					} else {
						value = {
							type: $(
								'input[data-name="' + name + '-type"]',
								$field
							).val(),
							icon: $(
								'input[data-name="' + name + '"]',
								$field
							).val()
						};
					}
					break;
				case "radio":
				case "text_align":
				case "text_align_no_justify":
					if (support_devices) {
						value = {};
						_.each(control.allDevices, function(device) {
							var input = $(
								'input[data-name="' +
									name +
									"-" +
									device +
									'"]:checked',
								$field
							);
							value[device] = input.length ? input.val() : "";
						});
					} else {
						value = $(
							'input[data-name="' + name + '"]:checked',
							$field
						).val();
					}

					break;
				case "checkbox":
					if (support_devices) {
						value = {};
						_.each(control.allDevices, function(device) {
							value[device] = $(
								'input[data-name="' +
									name +
									"-" +
									device +
									'"]',
								$field
							).is(":checked")
								? 1
								: "";
						});
					} else {
						value = $('input[data-name="' + name + '"]', $field).is(
							":checked"
						)
							? 1
							: "";
					}

					break;

				case "checkboxes":
					value = {};
					if (support_devices) {
						_.each(control.allDevices, function(device) {
							value[device] = {};
							$(
								'input[data-name="' +
									name +
									"-" +
									device +
									'"]',
								$field
							).each(function() {
								var v = $(this).val();
								if ($(this).is(":checked")) {
									value[v] = v;
								}
							});
						});
					} else {
						$('input[data-name="' + name + '"]', $field).each(
							function() {
								var v = $(this).val();
								if ($(this).is(":checked")) {
									value[v] = v;
								}
							}
						);
					}

					break;
				case "typography":
				case "modal":
				case "styling":
					if (support_devices) {
						value = {};
						_.each(control.allDevices, function(device) {
							value[device] = $(
								'[data-name="' + name + "-" + device + '"]',
								$field
							).val();
						});
					} else {
						value = $('[data-name="' + name + '"]', $field).val();
					}

					try {
						value = JSON.parse(value);
					} catch (e) {}
					break;
				default:
					if (support_devices) {
						value = {};
						_.each(control.allDevices, function(device) {
							value[device] = $(
								'[data-name="' + name + "-" + device + '"]',
								$field
							).val();
						});
					} else {
						value = $('[data-name="' + name + '"]', $field).val();
					}
					break;
			}

			return value;
		},
		getValue: function(field, container) {
			var control = this;
			var value = "";

			switch (field.type) {
				case "group":
					value = {};

					if (field.device_settings) {
						_.each(control.allDevices, function(device) {
							var $area = $(
								".starterblog-group-device-fields.starterblog--for-" +
									device,
								container
							);
							value[device] = {};
							var _value = {};
							_.each(field.fields, function(f) {
								var $_field = $(
									'.starterblog--group-field[data-field-name="' +
										f.name +
										'"]',
									$area
								);
								_value[f.name] = control.getFieldValue(
									f.name,
									f,
									$_field
								);
							});
							value[device] = _value;
							control.initConditional($area, _value);
						});
					} else {
						_.each(field.fields, function(f) {
							var $_field = $(
								'.starterblog--group-field[data-field-name="' +
									f.name +
									'"]',
								container
							);
							value[f.name] = control.getFieldValue(
								f.name,
								f,
								$_field
							);
						});
						control.initConditional(container, value);
					}

					break;
				case "repeater":
					value = [];
					$(".starterblog--repeater-item", container).each(function(
						index
					) {
						var $item = $(this);
						var _v = {};
						_.each(field.fields, function(f) {
							var inputField = $(
								'[data-field-name="' + f.name + '"]',
								$item
							);
							//var $_field = inputField.closest('.starterblog--field');
							//var $_field = inputField.closest('.starterblog--repeater-field');
							var _fv = control.getFieldValue(f.name, f, $item);
							_v[f.name] = _fv;
							// Update Live title
							if (field.live_title_field == f.name) {
								if (inputField.prop("tagName") == "select") {
									_fv = $('option[value="' + _fv + '"]')
										.first()
										.text();
								} else if (_.isUndefined(_fv) || _fv == "") {
									//_fv = control.params.l10n.untitled;
									_fv = StarterBlog_Control_Args.untitled;
								}
								control.updateRepeaterLiveTitle(_fv, $item, f);
							}
						});

						control.initConditional($item, _v);

						value[index] = _v;
						value[index]["_visibility"] = "visible";

						if ($("input.r-visible-input", $item).length) {
							if (
								!$("input.r-visible-input", $item).is(
									":checked"
								)
							) {
								value[index]["_visibility"] = "hidden";
							}
						}
					});
					break;
				default:
					value = this.getFieldValue(field.name, field, container);
					break;
			}

			return value;
		},
		encodeValue: function(value) {
			return encodeURI(JSON.stringify(value));
		},
		decodeValue: function(value) {
			return JSON.parse(decodeURI(value));
		},
		updateRepeaterLiveTitle: function(value, $item, field) {
			$(".starterblog--repeater-live-title", $item).text(value);
		},
		compare: function(value1, cond, value2) {
			var equal = false;
			switch (cond) {
				case "===":
					equal = value1 === value2 ? true : false;
					break;
				case ">":
					equal = value1 > value2 ? true : false;
					break;
				case "<":
					equal = value1 < value2 ? true : false;
					break;
				case "!=":
					equal = value1 != value2 ? true : false;
					break;
				case "empty":
					var _v = _.clone(value1);
					if (_.isObject(_v) || _.isArray(_v)) {
						_.each(_v, function(v, i) {
							if (_.isEmpty(v)) {
								delete _v[i];
							}
						});

						equal = _.isEmpty(_v) ? true : false;
					} else {
						equal = _.isNull(_v) || _v == "" ? true : false;
					}
					break;
				case "not_empty":
					var _v = _.clone(value1);
					if (_.isObject(_v) || _.isArray(_v)) {
						_.each(_v, function(v, i) {
							if (_.isEmpty(v)) {
								delete _v[i];
							}
						});
					}
					equal = _.isEmpty(_v) ? false : true;
					break;
				default:
					if (_.isArray(value2)) {
						if (!_.isEmpty(value2) && !_.isEmpty(value1)) {
							equal = _.contains(value2, value1);
						} else {
							equal = false;
						}
					} else {
						equal = value1 == value2 ? true : false;
					}
			}

			return equal;
		},
		multiple_compare: function(list, values, decodeValue) {
			if (_.isUndefined(decodeValue)) {
				decodeValue = false;
			}
			var control = this;
			var check = false;
			try {
				var test = list[0];

				if (_.isString(test)) {
					check = false;
					var cond = list[1];
					var cond_val = list[2];
					var cond_device = false;
					if (!_.isUndefined(list[3])) {
						// can be desktop, tablet, mobile
						cond_device = list[3];
					}
					var value;
					if (!_.isUndefined(values[test])) {
						value = values[test];
						if (cond_device) {
							if (
								_.isObject(value) &&
								!_.isUndefined(value[cond_device])
							) {
								value = value[cond_device];
							}
						}
						try {
							if (decodeValue) {
								value = control.decodeValue(value);
							}
						} catch (e) {}

						check = control.compare(value, cond, cond_val);
					}
				} else if (_.isArray(test)) {
					check = true;
					//console.log( '___', list );
					_.each(list, function(req) {
						var cond_key = req[0];
						var cond_cond = req[1];
						var cond_val = req[2];
						var cond_device = false;
						if (!_.isUndefined(req[3])) {
							// can be desktop, tablet, mobile
							cond_device = req[3];
						}
						var t_val = values[cond_key];
						if (_.isUndefined(t_val)) {
							t_val = "";
						}
						// console.log( '___reql', req );
						if (decodeValue && _.isString(t_val)) {
							try {
								t_val = control.decodeValue(t_val);
							} catch (e) {}
						}

						//console.log( '___t_val', t_val );
						if (cond_device) {
							if (
								_.isObject(t_val) &&
								!_.isUndefined(t_val[cond_device])
							) {
								t_val = t_val[cond_device];
							}
						}

						if (!control.compare(t_val, cond_cond, cond_val)) {
							check = false;
						}
					});
				}
			} catch (e) {
				//console.log( 'Trying_test_error', e  );
			}

			return check;
		},
		initConditional: function($el, values) {
			var control = this;
			var $fields = $(".starterblog--field", $el);
			$fields.each(function() {
				var $field = $(this);
				var check = true;
				var req = $field.attr("data-required") || false;
				if (!_.isUndefined(req) && req) {
					req = JSON.parse(req);
					check = control.multiple_compare(req, values);
					if (!check) {
						$field.addClass("starterblog--hide");
					} else {
						$field.removeClass("starterblog--hide");
					}
				}
			});
		},

		addDeviceSwitchers: function($el) {
			var field = this;
			if (_.isUndefined($el)) {
				$el = field.container;
			}
			var clone = $("#customize-footer-actions .devices").clone();
			clone.addClass("starterblog-devices");
			$("button", clone).each(function() {
				var d = $(this).attr("data-device");
				if (_.indexOf(field.devices, d) < 0) {
					$(this).remove();
				}
			});
			$(".starterblog-field-heading", $el)
				.append(clone)
				.addClass("starterblog-devices-added");
		},

		addRepeaterItem: function(field, value, $container, cb) {
			if (!_.isObject(value)) {
				value = {};
			}

			var control = this;
			var template = control.getTemplate();
			var fields = field.fields;
			var addable = true;
			var title_only = field.title_only;
			if (field.addable === false) {
				addable = false;
			}

			var $itemWrapper = $(
				template(field, "tmpl-customize-control-repeater-layout")
			);
			$container.find(".starterblog--settings-fields").append($itemWrapper);
			_.each(fields, function(f, index) {
				f.value = "";
				f.addable = addable;
				if (!_.isUndefined(value[f.name])) {
					f.value = value[f.name];
				}
				var $fieldArea;
				$fieldArea = $('<div class="starterblog--repeater-field"></div>');
				$(".starterblog--repeater-item-inner", $itemWrapper).append(
					$fieldArea
				);
				control.add(f, $fieldArea, function() {
					if (_.isFunction(cb)) {
						cb();
					}
				});

				var live_title = f.value;
				// Update Live title
				if (field.live_title_field === f.name) {
					if (f.type === "select") {
						live_title = f.choices[f.value];
					} else if (_.isUndefined(live_title) || live_title == "") {
						live_title = StarterBlog_Control_Args.untitled;
					}
					control.updateRepeaterLiveTitle(
						live_title,
						$itemWrapper,
						f
					);
				}
			});

			if (
				!_.isUndefined(value._visibility) &&
				value._visibility === "hidden"
			) {
				$itemWrapper.addClass("item---visible-hidden");
				$itemWrapper
					.find("input.r-visible-input")
					.removeAttr("checked");
			} else {
				$itemWrapper
					.find("input.r-visible-input")
					.attr("checked", "checked");
			}

			if (title_only) {
				$(
					".starterblog--repeater-item-settings, .starterblog--repeater-item-toggle",
					$itemWrapper
				).hide();
			}

			$document.trigger("starterblog/customizer/repeater/add", [
				$itemWrapper,
				control
			]);
			return $itemWrapper;
		},
		limitRepeaterItems: function(field, $container) {
			return;
			var control = this;
			var addButton = $(".starterblog--repeater-add-new", $container);
			var c = $(
				".starterblog--settings-fields .starterblog--repeater-item",
				$container
			).length;

			if (control.params.limit > 0) {
				if (c >= control.params.limit) {
					addButton.addClass("starterblog--hide");
					if (control.params.limit_msg) {
						if (
							$(".starterblog--limit-item-msg", control.container)
								.length === 0
						) {
							$(
								'<p class="starterblog--limit-item-msg">' +
									control.params.limit_msg +
									"</p>"
							).insertBefore(addButton);
						} else {
							$(
								".starterblog--limit-item-msg",
								control.container
							).removeClass("starterblog--hide");
						}
					}
				} else {
					$(".starterblog--limit-item-msg", control.container).addClass(
						"starterblog--hide"
					);
					addButton.removeClass("starterblog--hide");
				}
			}

			if (c > 0) {
				$(
					".starterblog--repeater-reorder",
					control.container
				).removeClass("starterblog--hide");
			} else {
				$(".starterblog--repeater-reorder", control.container).addClass(
					"starterblog--hide"
				);
			}
		},
		initRepeater: function(field, $container, cb) {
			var control = this;
			field = _.defaults(field, {
				addable: null,
				title_only: null,
				limit: null,
				live_title_field: null,
				fields: null
			});
			field.limit = parseInt(field.limit);
			if (isNaN(field.limit)) {
				field.limit = 0;
			}

			// Sortable
			$container.find(".starterblog--settings-fields").sortable({
				handle: ".starterblog--repeater-item-heading",
				containment: "parent",
				update: function(event, ui) {
					// control.getValue();
					if (_.isFunction(cb)) {
						cb();
					}
				}
			});

			// Toggle Move
			$container.on("click", ".starterblog--repeater-reorder", function(e) {
				e.preventDefault();
				$(".starterblog--repeater-items", $container).toggleClass(
					"reorder-active"
				);
				$(".starterblog--repeater-add-new", $container).toggleClass(
					"disabled"
				);
				if (
					$(".starterblog--repeater-items", $container).hasClass(
						"reorder-active"
					)
				) {
					$(this).html($(this).data("done"));
				} else {
					$(this).html($(this).data("text"));
				}
			});

			// Move Up
			$container.on(
				"click",
				".starterblog--repeater-item .starterblog--up",
				function(e) {
					e.preventDefault();
					var i = $(this).closest(".starterblog--repeater-item");
					var index = i.index();
					if (index > 0) {
						var up = i.prev();
						i.insertBefore(up);
						if (_.isFunction(cb)) {
							cb();
						}
					}
				}
			);

			// Move Down
			$container.on(
				"click",
				".starterblog--repeater-item .starterblog--down",
				function(e) {
					e.preventDefault();
					var n = $(
						".starterblog--repeater-items .starterblog--repeater-item",
						$container
					).length;
					var i = $(this).closest(".starterblog--repeater-item");
					var index = i.index();
					if (index < n - 1) {
						var down = i.next();
						i.insertAfter(down);
						if (_.isFunction(cb)) {
							cb();
						}
					}
				}
			);

			// Add item when customizer loaded
			if (_.isArray(field.value)) {
				_.each(field.value, function(itemValue) {
					control.addRepeaterItem(field, itemValue, $container, cb);
				});
				//control.getValue(false);
			}
			control.limitRepeaterItems();

			// Toggle visibility
			$container.on(
				"change",
				".starterblog--repeater-item .r-visible-input",
				function(e) {
					e.preventDefault();
					var p = $(this).closest(".starterblog--repeater-item");
					if ($(this).is(":checked")) {
						p.removeClass("item---visible-hidden");
					} else {
						p.addClass("item---visible-hidden");
					}
				}
			);

			// Toggle
			if (!field.title_only) {
				$container.on(
					"click",
					".starterblog--repeater-item-toggle, .starterblog--repeater-live-title",
					function(e) {
						e.preventDefault();
						var p = $(this).closest(".starterblog--repeater-item");
						p.toggleClass("starterblog--open");
					}
				);
			}

			// Remove
			$container.on("click", ".starterblog--remove", function(e) {
				e.preventDefault();
				var p = $(this).closest(".starterblog--repeater-item");
				p.remove();
				$document.trigger("starterblog/customizer/repeater/remove", [
					control
				]);
				if (_.isFunction(cb)) {
					cb();
				}
				control.limitRepeaterItems();
			});

			var defaultValue = {};
			_.each(field.fields, function(f, k) {
				defaultValue[f.name] = null;
				if (!_.isUndefined(f.default)) {
					defaultValue[f.name] = f.default;
				}
			});

			// Add Item
			$container.on("click", ".starterblog--repeater-add-new", function(e) {
				e.preventDefault();
				if (!$(this).hasClass("disabled")) {
					control.addRepeaterItem(
						field,
						defaultValue,
						$container,
						cb
					);
					if (_.isFunction(cb)) {
						cb();
					}
					control.limitRepeaterItems();
				}
			});
		},

		add: function(field, $fieldsArea, cb) {
			var control = this;
			var template = control.getTemplate();
			var template_id = "tmpl-field-" + control.type + "-" + field.type;
			if ($("#" + template_id).length == 0) {
				template_id = "tmpl-field-" + control.type + "-text";
			}

			if (field.device_settings) {
				var fieldItem = null;
				_.each(control.devices, function(device, index) {
					var _field = _.clone(field);
					_field.original_name = field.name;
					if (_.isObject(field.value)) {
						if (!_.isUndefined(field.value[device])) {
							_field.value = field.value[device];
						} else {
							_field.value = "";
						}
					} else {
						_field.value = "";
						if (index === 0) {
							_field.value = field.value;
						}
					}
					_field.name = field.name + "-" + device;
					_field._current_device = device;

					var $deviceFields = $(
						template(_field, template_id, "field")
					);
					var deviceFieldItem = $deviceFields
						.find(".starterblog-field-settings-inner")
						.first();

					if (!fieldItem) {
						$fieldsArea
							.append($deviceFields)
							.addClass("starterblog--multiple-devices");
					}

					deviceFieldItem.addClass("starterblog--for-" + device);
					deviceFieldItem.attr("data-for-device", device);

					if (fieldItem) {
						deviceFieldItem.insertAfter(fieldItem);
						fieldItem = deviceFieldItem;
					}
					fieldItem = deviceFieldItem;
				});
			} else {
				field.original_name = field.name;
				var $fields = template(field, template_id, "field");
				$fieldsArea.html($fields);
			}

			// Repeater
			if (field.type === "repeater") {
				var $rf_area = $(
					template(field, "tmpl-customize-control-repeater-inner")
				);
				$fieldsArea
					.find(".starterblog-field-settings-inner")
					.replaceWith($rf_area);
				control.initRepeater(field, $rf_area, cb);
			}

			if (field.css_format && _.isString(field.css_format)) {
				if (field.css_format.indexOf("value_no_unit") > 0) {
					$fieldsArea
						.find(".starterblog--slider-input")
						.addClass("no-unit");
					$(
						".starterblog--css-unit .starterblog--label-active",
						$fieldsArea
					).hide();
				}
			}

			// Add unility
			switch (field.type) {
				case "color":
				case "shadow":
					control.initColor($fieldsArea);
					break;
				case "image":
				case "video":
				case "audio":
				case "attchment":
				case "file":
					control.initMedia($fieldsArea);
					break;
				case "slider":
					control.initSlider($fieldsArea);
					break;
				case "css_ruler":
					control.initCSSRuler($fieldsArea, cb);
					break;
			}
			if (field.type !== "hidden") {
				if (
					!_.isUndefined(field.device_settings) &&
					field.device_settings
				) {
					control.addDeviceSwitchers($fieldsArea);
				}
			}
		},

		addFields: function(fields, values, $fieldsArea, cb) {
			var control = this;
			if (!_.isObject(values)) {
				values = {};
			}
			_.each(fields, function(f, index) {
				if (_.isUndefined(f.class)) {
					f.class = "";
				}
				var $fieldArea = $(
					'<div class="starterblog--group-field ft--' +
						f.type +
						" " +
						f.class +
						'" data-field-name="' +
						f.name +
						'"></div>'
				);
				$fieldsArea.append($fieldArea);
				f.original_name = f.name;
				if (!_.isUndefined(values[f.name])) {
					f.value = values[f.name];
				} else if (!_.isUndefined(f.default)) {
					f.value = f.default;
				} else {
					f.value = null;
				}
				control.add(f, $fieldArea, cb);
			});
		},

		initSlider: function($el) {
			if ($(".starterblog-input-slider", $el).length > 0) {
				$(".starterblog-input-slider", $el).each(function() {
					var slider = $(this);
					var p = slider.parent();
					var input = $(".starterblog--slider-input", p);
					var min = slider.data("min") || 0;
					var max = slider.data("max") || 300;
					var step = slider.data("step") || 1;
					if (!_.isNumber(min)) {
						min = 0;
					}

					if (!_.isNumber(max)) {
						max = 300;
					}

					if (!_.isNumber(step)) {
						step = 1;
					}

					var current_val = input.val();
					slider.slider({
						range: "min",
						value: current_val,
						step: step,
						min: min,
						max: max,
						slide: function(event, ui) {
							input.val(ui.value).trigger("data-change");
						}
					});

					input.on("change", function() {
						slider.slider("value", $(this).val());
					});

					// Reset
					var wrapper = slider.closest(
						".starterblog-input-slider-wrapper"
					);
					wrapper.on("click", ".reset", function(e) {
						e.preventDefault();
						var d = slider.data("default");
						if (!_.isObject(d)) {
							d = {
								unit: "px",
								value: ""
							};
						}

						$(".starterblog--slider-input", wrapper).val(d.value);
						slider.slider("option", "value", d.value);
						$(
							'.starterblog--css-unit input.starterblog-input[value="' +
								d.unit +
								'"]',
							wrapper
						).trigger("click");
						$(".starterblog--slider-input", wrapper).trigger(
							"change"
						);
					});
				});
			}
		},

		initMedia: function($el) {
			// When add/Change
			$el.on(
				"click",
				".starterblog--media .starterblog--add, .starterblog--media .starterblog--change, .starterblog--media .starterblog-image-preview",
				function(e) {
					e.preventDefault();
					var p = $(this).closest(".starterblog--media");
					StarterBlogMedia.setPreview(p);
					StarterBlogMedia.controlMediaImage.open();
				}
			);

			// When add/Change
			$el.on("click", ".starterblog--media .starterblog--remove", function(
				e
			) {
				e.preventDefault();
				var p = $(this).closest(".starterblog--media");
				StarterBlogMedia.remove(p);
			});
		},

		initCSSRuler: function($el, change_cb) {
			// When toggle value change
			$el.on("change", ".starterblog--label-parent", function() {
				if ($(this).attr("type") == "radio") {
					var name = $(this).attr("name");
					$('input[name="' + name + '"]', $el)
						.parent()
						.removeClass("starterblog--label-active");
				}
				var checked = $(this).is(":checked");
				if (checked) {
					$(this)
						.parent()
						.addClass("starterblog--label-active");
				} else {
					$(this)
						.parent()
						.removeClass("starterblog--label-active");
				}
				if (_.isFunction(change_cb)) {
					change_cb();
				}
			});

			$el.on(
				"change keyup",
				".starterblog--css-ruler .starterblog-input-css",
				function() {
					var p = $(this).closest(".starterblog--css-ruler");
					var link_checked = $(
						".starterblog--css-ruler-link input",
						p
					).is(":checked");
					if (link_checked) {
						var v = $(this).val();
						$(".starterblog-input-css", p)
							.not($(this))
							.each(function() {
								if (!$(this).is(":disabled")) {
									$(this).val(v);
								}
							});
					}
					if (_.isFunction(change_cb)) {
						change_cb();
					}
				}
			);
		},

		initColor: function($el) {
			$(".starterblog-input-color", $el).each(function() {
				var colorInput = $(this);
				var df = colorInput.data("default") || "";
				var current_val = $(
					".starterblog-input--color",
					colorInput
				).val();
				// data-alpha="true"
				$(".starterblog--color-panel", colorInput).attr(
					"data-alpha",
					"true"
				);
				$(".starterblog--color-panel", colorInput).wpColorPicker({
					defaultColor: df,
					change: function(event, ui) {
						var new_color = ui.color.toString();
						$(".starterblog-input--color", colorInput).val(new_color);
						if (ui.color.toString() !== current_val) {
							current_val = new_color;
							$(".starterblog-input--color", colorInput).trigger(
								"change"
							);
						}
					},
					clear: function(event, ui) {
						$(".starterblog-input--color", colorInput).val("");
						$(".starterblog-input--color", colorInput).trigger(
							"data-change"
						);
					}
				});
			});
		}
	};

	//-------------------------------------------------------------------------

	var starterblog_controlConstructor = {
		devices: ["desktop", "tablet", "mobile"],
		// When we're finished loading continue processing
		type: "starterblog",
		settingField: null,

		getTemplate: _.memoize(function() {
			var control = this;
			var compiled,
				/*
				 * Underscore's default ERB-style templates are incompatible with PHP
				 * when asp_tags is enabled, so WordPress uses Mustache-inspired templating syntax.
				 *
				 * @see trac ticket #22344.
				 */
				options = {
					evaluate: /<#([\s\S]+?)#>/g,
					interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
					escape: /\{\{([^\}]+?)\}\}(?!\})/g,
					variable: "data"
				};

			return function(data, id, data_variable_name) {
				if (_.isUndefined(id)) {
					id = "tmpl-field-starterblog-" + control.type;
				}
				if (
					!_.isUndefined(data_variable_name) &&
					_.isString(data_variable_name)
				) {
					options.variable = data_variable_name;
				} else {
					options.variable = "data";
				}

				compiled = _.template($("#" + id).html(), null, options);
				return compiled(data);
			};
		}),
		addDeviceSwitchers: starterblogField.addDeviceSwitchers,
		init: function() {
			var control = this;

			if (
				_.isArray(control.params.devices) &&
				!_.isEmpty(control.params.devices)
			) {
				control.devices = control.params.devices;
			}

			// The hidden field that keeps the data saved (though we never update it)
			control.settingField = control.container
				.find("[data-customize-setting-link]")
				.first();

			switch (control.params.setting_type) {
				case "group":
					control.initGroup();
					break;
				case "repeater":
					control.initRepeater();
					break;
				default:
					control.initField();
					break;
			}

			control.container.on(
				"change keyup data-change",
				"input:not(.change-by-js), select:not(.change-by-js), textarea:not(.change-by-js)",
				function() {
					control.getValue();
				}
			);
		},
		addParamsURL: function(url, data) {
			if (!$.isEmptyObject(data)) {
				url += (url.indexOf("?") >= 0 ? "&" : "?") + $.param(data);
			}
			return url;
		},

		compare: starterblogField.compare,
		multiple_compare: starterblogField.multiple_compare,
		initConditional: starterblogField.initConditional,

		getValue: function(save) {
			var control = this;
			var value = "";

			var field = _.clone(control.params);

			field.type = control.params.setting_type;
			field.name = control.id;
			field.value = control.value;
			field.default = control.params.default;
			field.devices = control.params.devices;

			if (field.type === "slider") {
				field.min = control.params.min;
				field.max = control.params.max;
				field.step = control.params.step;
				field.unit = control.params.unit;
			}

			if (field.type === "css_ruler") {
				field.fields_disabled = control.params.fields_disabled;
			}

			if (field.type === "group" || field.type === "repeater") {
				field.fields = control.params.fields;
				field.live_title_field = control.params.live_title_field;
			}

			if (
				control.params.setting_type === "select" ||
				control.params.setting_type === "radio"
			) {
				field.choices = control.params.choices;
			}
			if (control.params.setting_type === "checkbox") {
				field.checkbox_label = control.params.checkbox_label;
			}

			field.device_settings = control.params.device_settings;

			value = starterblogField.getValue(
				field,
				$(".starterblog--settings-fields", control.container)
			);

			if (_.isUndefined(save) || save) {
				control.setting.set(control.encodeValue(value));

				// Need improve next version
				if (_.isArray(control.params.reset_controls)) {
					_.each(control.params.reset_controls, function(_cid) {
						try {
							var c = wpcustomize.control(_cid);
							c.setting.set(
								control.encodeValue(c.params.default)
							);
						} catch (e) {}
					});
				}

				$document.trigger("starterblog/customizer/value_changed", [
					control,
					value
				]);
				// console.log( 'Save_Value: ', value );
			} else {
			}

			return value;
		},
		encodeValue: function(value) {
			return encodeURI(JSON.stringify(value));
		},
		decodeValue: function(value) {
			return JSON.parse(decodeURI(value));
		},
		updateRepeaterLiveTitle: function(value, $item, field) {
			$(".starterblog--repeater-live-title", $item).text(value);
		},
		initGroup: function() {
			var control = this;
			if (control.params.device_settings) {
				control.container
					.find(".starterblog--settings-fields")
					.addClass("starterblog--multiple-devices");
				if (!_.isObject(control.params.value)) {
					control.params.value = {};
				}

				_.each(control.devices, function(device, device_index) {
					var $group_device = $(
						'<div class="starterblog-group-device-fields starterblog-field-settings-inner starterblog--for-' +
							device +
							'"></div>'
					);
					control.container
						.find(".starterblog--settings-fields")
						.append($group_device);
					var device_value = {};
					if (!_.isUndefined(control.params.value[device])) {
						device_value = control.params.value[device];
					}
					if (!_.isObject(device_value)) {
						device_value = {};
					}

					starterblogField.addFields(
						control.params.fields,
						device_value,
						$group_device,
						function() {
							control.getValue();
						}
					);
				});
			} else {
				starterblogField.addFields(
					control.params.fields,
					control.params.value,
					control.container.find(".starterblog--settings-fields"),
					function() {
						control.getValue();
					}
				);
			}

			control.getValue(false);
		},
		addField: function(field, $fieldsArea, cb) {
			starterblogField.devices = _.clone(this.devices);
			starterblogField.add(field, $fieldsArea, cb);
		},
		initField: function() {
			var control = this;
			var field = _.clone(control.params);

			field = _.extend(field, {
				type: control.params.setting_type,
				name: control.id,
				value: control.params.value,
				default: control.params.default,
				devices: control.params.devices,
				unit: control.params.unit,
				title: null,
				label: null,
				description: null
			});

			if (field.type == "slider") {
				field.min = control.params.min;
				field.max = control.params.max;
				field.step = control.params.step;
			}

			if (field.type == "css_ruler") {
				field.fields_disabled = control.params.fields_disabled;
			}

			if (
				control.params.setting_type == "select" ||
				control.params.setting_type == "radio"
			) {
				field.choices = control.params.choices;
			}
			if (control.params.setting_type == "checkbox") {
				field.checkbox_label = control.params.checkbox_label;
			}

			field.device_settings = control.params.device_settings;
			var $fieldsArea = control.container.find(
				".starterblog--settings-fields"
			);

			control.addField(field, $fieldsArea, function() {
				control.getValue();
			});
			if (field.type !== "hidden") {
				if (
					!_.isUndefined(field.device_settings) &&
					field.device_settings
				) {
					control.addDeviceSwitchers(control.container);
				}
			}
		},
		addRepeaterItem: function(value) {
			if (!_.isObject(value)) {
				value = {};
			}

			var control = this;
			var template = control.getTemplate();
			var fields = control.params.fields;
			var addable = true;
			var title_only = control.params.title_only;
			if (control.params.addable === false) {
				addable = false;
			}

			var $itemWrapper = $(
				template(control.params, "tmpl-customize-control-repeater-item")
			);
			control.container
				.find(".starterblog--settings-fields")
				.append($itemWrapper);
			_.each(fields, function(f, index) {
				f.value = "";
				f.addable = addable;
				if (!_.isUndefined(value[f.name])) {
					f.value = value[f.name];
				}
				var $fieldArea;
				$fieldArea = $('<div class="starterblog--repeater-field"></div>');
				$(".starterblog--repeater-item-inner", $itemWrapper).append(
					$fieldArea
				);
				control.addField(f, $fieldArea, function() {
					control.getValue();
				});
			});

			if (
				!_.isUndefined(value._visibility) &&
				value._visibility === "hidden"
			) {
				$itemWrapper.addClass("item---visible-hidden");
				$itemWrapper
					.find("input.r-visible-input")
					.removeAttr("checked");
			} else {
				$itemWrapper
					.find("input.r-visible-input")
					.attr("checked", "checked");
			}

			if (title_only) {
				$(
					".starterblog--repeater-item-settings, .starterblog--repeater-item-toggle",
					$itemWrapper
				).hide();
			}

			$document.trigger("starterblog/customizer/repeater/add", [
				$itemWrapper,
				control
			]);
			return $itemWrapper;
		},
		limitRepeaterItems: function() {
			var control = this;

			var addButton = $(
				".starterblog--repeater-add-new",
				control.container
			);
			var c = $(
				".starterblog--settings-fields .starterblog--repeater-item",
				control.container
			).length;

			if (control.params.limit > 0) {
				if (c >= control.params.limit) {
					addButton.addClass("starterblog--hide");
					if (control.params.limit_msg) {
						if (
							$(".starterblog--limit-item-msg", control.container)
								.length === 0
						) {
							$(
								'<p class="starterblog--limit-item-msg">' +
									control.params.limit_msg +
									"</p>"
							).insertBefore(addButton);
						} else {
							$(
								".starterblog--limit-item-msg",
								control.container
							).removeClass("starterblog--hide");
						}
					}
				} else {
					$(".starterblog--limit-item-msg", control.container).addClass(
						"starterblog--hide"
					);
					addButton.removeClass("starterblog--hide");
				}
			}

			if (c > 0) {
				$(
					".starterblog--repeater-reorder",
					control.container
				).removeClass("starterblog--hide");
			} else {
				$(".starterblog--repeater-reorder", control.container).addClass(
					"starterblog--hide"
				);
			}
		},
		initRepeater: function() {
			var control = this;
			control.params.limit = parseInt(control.params.limit);
			if (isNaN(control.params.limit)) {
				control.params.limit = 0;
			}

			// Sortable
			control.container.find(".starterblog--settings-fields").sortable({
				handle: ".starterblog--repeater-item-heading",
				containment: "parent",
				update: function(event, ui) {
					control.getValue();
				}
			});

			// Toggle Move
			control.container.on(
				"click",
				".starterblog--repeater-reorder",
				function(e) {
					e.preventDefault();
					$(
						".starterblog--repeater-items",
						control.container
					).toggleClass("reorder-active");
					$(
						".starterblog--repeater-add-new",
						control.container
					).toggleClass("disabled");
					if (
						$(
							".starterblog--repeater-items",
							control.container
						).hasClass("reorder-active")
					) {
						$(this).html($(this).data("done"));
					} else {
						$(this).html($(this).data("text"));
					}
				}
			);

			// Move Up
			control.container.on(
				"click",
				".starterblog--repeater-item .starterblog--up",
				function(e) {
					e.preventDefault();
					var i = $(this).closest(".starterblog--repeater-item");
					var index = i.index();
					if (index > 0) {
						var up = i.prev();
						i.insertBefore(up);
						control.getValue();
					}
				}
			);

			// Move Down
			control.container.on(
				"click",
				".starterblog--repeater-item .starterblog--down",
				function(e) {
					e.preventDefault();
					var n = $(
						".starterblog--repeater-items .starterblog--repeater-item",
						control.container
					).length;
					var i = $(this).closest(".starterblog--repeater-item");
					var index = i.index();
					if (index < n - 1) {
						var down = i.next();
						i.insertAfter(down);
						control.getValue();
					}
				}
			);

			/**
			 * @TODO: Translateable live title if not addable
			 */
			if (!control.params.addable) {
				if (control.params.live_title_field) {
					var _titles = {};
					_.each(control.params.default, function(_value) {
						if (
							!_.isUndefined(_value._key) &&
							!_.isUndefined(
								_value[control.params.live_title_field]
							)
						) {
							_titles[_value._key] =
								_value[control.params.live_title_field];
						}
					});

					_.each(control.params.value, function(_value, index) {
						if (!_.isUndefined(_titles[_value._key])) {
							control.params.value[index][
								control.params.live_title_field
							] = _titles[_value._key];
						}
					});
				}
			}

			// Add item when customizer loaded
			if (_.isArray(control.params.value)) {
				_.each(control.params.value, function(itemValue) {
					control.addRepeaterItem(itemValue);
				});
				control.getValue(false);
			}
			control.limitRepeaterItems();

			// Toggle visibility
			control.container.on(
				"change",
				".starterblog--repeater-item .r-visible-input",
				function(e) {
					e.preventDefault();
					var p = $(this).closest(".starterblog--repeater-item");
					if ($(this).is(":checked")) {
						p.removeClass("item---visible-hidden");
					} else {
						p.addClass("item---visible-hidden");
					}
				}
			);

			// Toggle
			if (!control.params.title_only) {
				control.container.on(
					"click",
					".starterblog--repeater-item-toggle, .starterblog--repeater-live-title",
					function(e) {
						e.preventDefault();
						var p = $(this).closest(".starterblog--repeater-item");
						p.toggleClass("starterblog--open");
					}
				);
			}

			// Remove
			control.container.on("click", ".starterblog--remove", function(e) {
				e.preventDefault();
				var p = $(this).closest(".starterblog--repeater-item");
				p.remove();
				$document.trigger("starterblog/customizer/repeater/remove", [
					control
				]);
				control.getValue();
				control.limitRepeaterItems();
			});

			var defaultValue = {};
			_.each(control.params.fields, function(f, k) {
				defaultValue[f.name] = null;
				if (!_.isUndefined(f.default)) {
					defaultValue[f.name] = f.default;
				}
			});

			// Add Item
			control.container.on(
				"click",
				".starterblog--repeater-add-new",
				function(e) {
					e.preventDefault();
					if (!$(this).hasClass("disabled")) {
						control.addRepeaterItem(defaultValue);
						control.getValue();
						control.limitRepeaterItems();
					}
				}
			);
		}
	};

	var starterblog_control = function(control) {
		control = _.extend(control, starterblog_controlConstructor);
		control.init();
	};
	//---------------------------------------------------------------------------

	wp.customize.controlConstructor.starterblog = wp.customize.Control.extend({
		ready: function() {
			starterblog_controls_list[this.id] = this;
		}
	});

	var IconPicker = {
		pickingEl: null,
		listIcons: null,
		render: function(list_icons) {
			var that = this;
			if (!_.isUndefined(list_icons) && !_.isEmpty(list_icons)) {
				_.each(list_icons, function(icon_config, font_type) {
					$("#starterblog--sidebar-icon-type").append(
						' <option value="' +
							font_type +
							'">' +
							icon_config.name +
							"</option>"
					);
					that.addCSS(icon_config, font_type);
					that.addIcons(icon_config, font_type);
				});
			}
		},

		addCSS: function(icon_config, font_type) {
			$("head").append(
				"<link rel='stylesheet' id='font-icon-" +
					font_type +
					"'  href='" +
					icon_config.url +
					"' type='text/css' media='all' />"
			);
		},

		addIcons: function(icon_config, font_type) {
			var icon_html =
				'<ul class="starterblog--list-icons icon-' +
				font_type +
				'" data-type="' +
				font_type +
				'">';
			_.each(icon_config.icons, function(icon_class, i) {
				var class_name = "";
				if (icon_config.class_config) {
					class_name = icon_config.class_config.replace(
						/__icon_name__/g,
						icon_class
					);
				} else {
					class_name = icon_class;
				}

				icon_html +=
					'<li title="' +
					icon_class +
					'" data-type="' +
					font_type +
					'" data-icon="' +
					class_name +
					'"><span class="icon-wrapper"><i class="' +
					class_name +
					'"></i></span></li>';
			});
			icon_html += "</ul>";

			$("#starterblog--icon-browser").append(icon_html);
		},
		changeType: function() {
			$document.on("change", "#starterblog--sidebar-icon-type", function() {
				var type = $(this).val();
				if (!type || type == "all") {
					$("#starterblog--icon-browser .starterblog--list-icons").show();
				} else {
					$("#starterblog--icon-browser .starterblog--list-icons").hide();
					$(
						"#starterblog--icon-browser .starterblog--list-icons.icon-" +
							type
					).show();
				}
			});
		},
		show: function() {
			var controlWidth = $("#customize-controls").width();
			if (!is_rtl) {
				$("#starterblog--sidebar-icons")
					.css("left", controlWidth)
					.addClass("starterblog--active");
			} else {
				$("#starterblog--sidebar-icons")
					.css("right", controlWidth)
					.addClass("starterblog--active");
			}
		},
		close: function() {
			if (!is_rtl) {
				$("#starterblog--sidebar-icons")
					.css("left", -300)
					.removeClass("starterblog--active");
			} else {
				$("#starterblog--sidebar-icons")
					.css("right", -300)
					.removeClass("starterblog--active");
			}
			$(".starterblog--icon-picker").removeClass("starterblog--icon-picking");
			this.pickingEl = null;
		},
		autoClose: function() {
			var that = this;
			$document.on("click", function(event) {
				if (
					!$(event.target).closest(".starterblog--icon-picker").length
				) {
					if (
						!$(event.target).closest("#starterblog--sidebar-icons")
							.length
					) {
						that.close();
					}
				}
			});

			$("#starterblog--sidebar-icons .customize-controls-icon-close").on(
				"click",
				function() {
					that.close();
				}
			);

			$document.on("keyup", function(event) {
				if (event.keyCode === 27) {
					that.close();
				}
			});
		},
		picker: function() {
			var that = this;

			var open = function($el) {
				if (that.pickingEl) {
					that.pickingEl.removeClass("starterblog--icon-picking");
				}
				that.pickingEl = $el.closest(".starterblog--icon-picker");
				that.pickingEl.addClass("starterblog--picking-icon");
				that.show();
			};

			$document.on(
				"click",
				".starterblog--icon-picker .starterblog--pick-icon",
				function(e) {
					e.preventDefault();
					var button = $(this);
					if (_.isNull(that.listIcons)) {
						that.ajaxLoad(function() {
							open(button);
						});
					} else {
						open(button);
					}
				}
			);

			$document.on("click", "#starterblog--icon-browser li", function(e) {
				e.preventDefault();
				var li = $(this);
				var icon_preview = li.find("i").clone();
				var icon = li.attr("data-icon") || "";
				var type = li.attr("data-type") || "";
				console.log("icon", icon);
				$(".starterblog--input-icon-type", that.pickingEl).val(type);
				$(".starterblog--input-icon-name", that.pickingEl)
					.val(icon)
					.trigger("change");
				$(".starterblog--icon-preview-icon", that.pickingEl).html(
					icon_preview
				);

				that.close();
			});

			// remove
			$document.on(
				"click",
				".starterblog--icon-picker .starterblog--icon-remove",
				function(e) {
					e.preventDefault();
					if (that.pickingEl) {
						that.pickingEl.removeClass("starterblog--icon-picking");
					}
					that.pickingEl = $(this).closest(".starterblog--icon-picker");
					that.pickingEl.addClass("starterblog--picking-icon");

					$(".starterblog--input-icon-type", that.pickingEl).val("");
					$(".starterblog--input-icon-name", that.pickingEl)
						.val("")
						.trigger("change");
					$(".starterblog--icon-preview-icon", that.pickingEl).html("");
				}
			);
		},

		ajaxLoad: function(cb) {
			var that = this;
			$.get(
				StarterBlog_Control_Args.ajax,
				{
					action: "starterblog/customizer/ajax/get_icons",
					wp_customize: "on",
					_nonce: _wpCustomizeSettings.nonce.preview,
					customize_theme: _wpCustomizeSettings.theme.stylesheet
				},
				function(res) {
					if (res.success) {
						that.listIcons = res.data;
						that.render(res.data);
						that.changeType();
						that.autoClose();
						if (_.isFunction(cb)) {
							cb();
						}
					}
				}
			);
		},
		init: function() {
			var that = this;
			that.ajaxLoad();
			that.picker();
			// Search icon
			$document.on("keyup", "#starterblog--icon-search", function(e) {
				var v = $(this).val();
				v = v.trim();
				if (v) {
					$("#starterblog--icon-browser li").hide();
					$(
						"#starterblog--icon-browser li[data-icon*='" + v + "']"
					).show();
				} else {
					$("#starterblog--icon-browser li").show();
				}
			});
		}
	};

	var FontSelector = {
		fonts: null,
		optionHtml: "",
		$el: null,
		values: {},
		config: {}, // Config to disable fields
		container: null,
		fields: {},
		load: function() {
			var that = this;
			$.get(
				StarterBlog_Control_Args.ajax,
				{
					action: "starterblog/customizer/ajax/fonts",
					wp_customize: "on",
					_nonce: _wpCustomizeSettings.nonce.preview,
					customize_theme: _wpCustomizeSettings.theme.stylesheet
				},
				function(res) {
					if (res.success) {
						that.fonts = res.data;
					}
				}
			);
		},
		toSelectOptions: function(options, v, type) {
			var html = "";
			if (_.isUndefined(v)) {
				v = "";
			}

			if (type === "google") {
				_.each(options, function(value) {
					var selected = "";
					if (value === v) {
						selected = ' selected="selected" ';
					}
					html +=
						"<option" +
						selected +
						' value="' +
						value +
						'">' +
						value +
						"</option>";
				});
			} else {
				_.each(StarterBlog_Control_Args.list_font_weight, function(
					value,
					key
				) {
					var selected = "";
					if (value === v) {
						selected = ' selected="selected" ';
					}
					html +=
						"<option" +
						selected +
						' value="' +
						key +
						'">' +
						value +
						"</option>";
				});

				var value, selected, i;

				for (i = 1; i <= 9; i++) {
					value = i * 100;
					selected = "";
					if (value === v) {
						selected = ' selected="selected" ';
					}
					html +=
						"<option" +
						selected +
						' value="' +
						value +
						'">' +
						value +
						"</option>";
				}
			}

			return html;
		},
		toCheckboxes: function(options, v) {
			var html = '<div class="list-subsets">';
			if (!_.isObject(v)) {
				v = {};
			}
			_.each(options, function(value) {
				var checked = "";
				if (!_.isUndefined(v[value])) {
					checked = ' checked="checked" ';
				}
				html +=
					"<p><label><input " +
					checked +
					'type="checkbox" class="starterblog-typo-input change-by-js" data-name="languages" name="_n-' +
					new Date().getTime() +
					'" value="' +
					value +
					'"> ' +
					value +
					"</label></p>";
			});
			html += "</div>";
			return html;
		},
		ready: function() {
			var that = this;
			starterblogField.devices = _.clone(starterblogField.allDevices);
			if (!_.isObject(that.values)) {
				that.values = {};
			}

			that.fields = {};

			//StarterBlog_Control_Args.typo_fields
			if (!_.isEmpty(that.config)) {
				_.each(StarterBlog_Control_Args.typo_fields, function(_f, _key) {
					var show = true;
					if (!_.isUndefined(that.config[_f.name])) {
						if (that.config[_f.name] === false) {
							show = false;
						}
					}

					if (show) {
						that.fields[_f.name] = _f;
					}
				});
			} else {
				that.fields = StarterBlog_Control_Args.typo_fields;
			}

			$(".starterblog-modal-settings--fields", that.container).append(
				'<input type="hidden" class="starterblog--font-type">'
			);
			starterblogField.addFields(
				that.fields,
				that.values,
				$(".starterblog-modal-settings--fields", that.container),
				function() {
					that.get();
				}
			);

			$("input, select, textarea", $(".starterblog-modal-settings--fields"))
				.removeClass("starterblog-input")
				.addClass("starterblog-typo-input change-by-js");
			that.optionHtml +=
				'<option value="">' +
				StarterBlog_Control_Args.theme_default +
				"</option>";
			_.each(that.fonts, function(group, type) {
				that.optionHtml += '<optgroup label="' + group.title + '">';
				_.each(group.fonts, function(font, font_name) {
					var label = _.isString(font) ? font : font_name;
					that.optionHtml +=
						'<option value="' +
						font_name +
						'">' +
						label +
						"</option>";
				});
				that.optionHtml += "</optgroup>";
			});

			$('.starterblog-typo-input[data-name="font"]', that.container).html(
				that.optionHtml
			);

			if (
				!_.isUndefined(that.values["font"]) &&
				_.isString(that.values["font"])
			) {
				$(
					'.starterblog-typo-input[data-name="font"] option[value="' +
						that.values["font"] +
						'"]',
					that.container
				).attr("selected", "selected");
			}

			that.container.on(
				"change init-change",
				'.starterblog-typo-input[data-name="font"]',
				function() {
					var font = $(this).val();
					that.setUpFont(font);
				}
			);

			$(
				'.starterblog-typo-input[data-name="font"]',
				that.container
			).trigger("init-change");

			$(
				'.starterblog-typo-input[data-name="font"]',
				that.container
			).select2();

			// Bind events on inputs
			that.container.on(
				"change data-change",
				"input, select",
				function() {
					that.get();
				}
			);

			// Bind event on container
			that.container.on(
				"container-data-change",
				function() {
					that.get();
				}
			);


		},

		setUpFont: function(font) {
			var that = this;
			var font_settings, variants, subsets, type;

			if (_.isEmpty(font)) {
				type = "normal";
			}

			if (!_.isObject(that.fonts) || _.isEmpty(that.fonts)) {
				that.fonts = {
					normal: {
						fonts: {}
					},
					google: {
						fonts: {}
					}
				};
			}

			if (!_.isNull(font) && font) {
				if (_.isString(font)) {
					if (!_.isUndefined(that.fonts.google.fonts[font])) {
						type = "google";
					} else {
						type = "normal";
					}
					font_settings = that.fonts.google.fonts[font];
				} else {
					font_settings = that.fonts.google.fonts[font.font];
				}
			} else {
				font_settings = {};
			}

			if (!_.isUndefined(font_settings) && !_.isEmpty(font_settings)) {
				variants = font_settings.variants;
				subsets = font_settings.subsets;
			}

			$(
				'.starterblog-typo-input[data-name="font_weight"]',
				that.container
			).html(
				that.toSelectOptions(
					variants,
					_.isObject(that.values) ? that.values.font_weight : "",
					type
				)
			);
			$(".starterblog--font-type", that.container).val(type);

			if (type !== "google") {
				$(
					'.starterblog--group-field[data-field-name="languages"]',
					that.container
				)
					.addClass("starterblog--hide")
					.find(".starterblog-field-settings-inner")
					.html("");
			} else {
				$(
					'.starterblog--group-field[data-field-name="languages"]',
					that.container
				).removeClass("starterblog--hide");
				$(
					'.starterblog--group-field[data-field-name="languages"]',
					that.container
				)
					.removeClass("starterblog--hide")
					.find(".starterblog-field-settings-inner")
					.html(
						that.toCheckboxes(
							subsets,
							_.isObject(that.values) ? that.values.languages : ""
						)
					);
			}
		},

		open: function() {
			//this.$el = $el;
			var that = this;
			var $el = that.$el;

			var status = $el.attr("data-opening") || false;
			if (status !== "opening") {
				$el.attr("data-opening", "opening");
				that.values = $(".starterblog-typography-input", that.$el).val();
				that.values = JSON.parse(that.values);
				$el.addClass("starterblog-modal--inside");
				if (!$(".starterblog-modal-settings", $el).length) {
					var $wrap = $($("#tmpl-starterblog-modal-settings").html());
					that.container = $wrap;
					that.container.hide();
					this.$el.append($wrap);
					that.ready();
				} else {
					that.container = $(".starterblog-modal-settings", $el);
					that.container.hide();
				}
				that.container.slideDown(300, function() {
					that.$el.addClass("modal--opening");
					$(".action--reset", that.$el).show();
				});
			} else {
				$(".starterblog-modal-settings", $el).slideUp(300, function() {
					$el.attr("data-opening", "");
					$el.removeClass("modal--opening");
					$(".action--reset", $el).hide();
				});
			}
		},

		reset: function() {
			//this.$el = $el;
			var that = this;
			var $el = that.$el;

			$(".starterblog-modal-settings", $el).remove();
			that.values =
				$(".starterblog-typography-input", that.$el).attr(
					"data-default"
				) || "{}";
			try {
				that.values = JSON.parse(that.values);
			} catch (e) {}

			$el.addClass("starterblog-modal--inside");
			if (!$(".starterblog-modal-settings", $el).length) {
				var $wrap = $($("#tmpl-starterblog-modal-settings").html());
				that.container = $wrap;
				this.$el.append($wrap);
				that.ready();
			} else {
				that.container = $(".starterblog-modal-settings", $el);
			}
			that.get();
		},

		get: function() {
			var data = {};
			var that = this;
			_.each(this.fields, function(f) {
				if (f.name === "languages") {
					f.type = "checkboxes";
				}
				data[f.name] = starterblogField.getValue(
					f,
					$(
						'.starterblog--group-field[data-field-name="' +
							f.name +
							'"]',
						that.container
					)
				);
			});

			data.variant = {};
			if (data.font) {
				if (!_.isUndefined(that.fonts.google.fonts[data.font])) {
					data.variant = that.fonts.google.fonts[data.font].variants;
				}
			}

			data.font_type = $(".starterblog--font-type", that.container).val();
			$(".starterblog-typography-input", this.$el)
				.val(JSON.stringify(data))
				.trigger("change");
			return data;
		},

		init: function() {
			this.load();
		}
	};

	var intTypoControls = {};
	var intTypos = function() {
		$document.on(
			"click",
			".customize-control-starterblog-typography .action--edit, .customize-control-starterblog-typography .action--reset",
			function() {
				var controlID = $(this).attr("data-control") || "";
				if (_.isUndefined(intTypoControls[controlID])) {
					var c = wpcustomize.control(controlID);
					if (controlID && !_.isUndefined(c)) {
						var m = _.clone(FontSelector);
						m.config = c.params.fields;
						m.$el = $(this)
							.closest(".customize-control-starterblog-typography")
							.eq(0);
						intTypoControls[controlID] = m;
					}
				}

				if (!_.isUndefined(intTypoControls[controlID])) {
					if ($(this).hasClass("action--reset")) {
						intTypoControls[controlID].reset();
					} else {
						intTypoControls[controlID].open();
					}
				}
			}
		);
	};

	//---------------------------------------------------------------------------
	var starterblogModal = {
		tabs: {
			normal: "Normal",
			hover: "Hover"
		},
		config: {},
		$el: null,
		container: null,
		controlID: "",
		addFields: function(values) {
			var that = this;
			if (!_.isObject(that.values)) {
				that.values = {};
			}
			that.values = _.defaults(that.values, {});
			var fieldsArea = $(
				".starterblog-modal-settings--fields",
				that.container
			);
			fieldsArea.html("");

			that.config = _.defaults(that.config, {
				tabs: {}
			});

			var tabsHTML = $('<div class="modal--tabs"></div>');
			var c = 0;
			_.each(that.config.tabs, function(label, key) {
				if (label && _.isObject(that.config[key + "_fields"])) {
					c++;
					tabsHTML.append(
						'<div><span data-tab="' +
							key +
							'" class="modal--tab modal-tab--' +
							key +
							'">' +
							label +
							"</span></div>"
					);
				}
			});

			fieldsArea.append(tabsHTML);
			if (c <= 1) {
				tabsHTML.addClass("starterblog--hide");
			}
			starterblogField.devices = StarterBlog_Control_Args.devices;
			_.each(that.config.tabs, function(label, key) {
				if (
					_.isObject(that.config[key + "_fields"]) &&
					!_.isEmpty(key + "_fields")
				) {
					var content = $(
						'<div class="modal-tab-content modal-tab--' +
							key +
							'"></div>'
					);
					fieldsArea.append(content);
					starterblogField.addFields(
						that.config[key + "_fields"],
						that.values[key],
						content,
						function() {
							that.get(_.clone(that.config));
						}
					);
					var fv;
					if (
						_.isUndefined(that.values[key]) ||
						_.isEmpty(that.values[key])
					) {
						fv = {};
						_.each(that.config[key + "_fields"], function(f) {
							fv[f.name] = _.isUndefined(f.default)
								? null
								: f.default;
						});
					} else {
						fv = that.values[key];
					}
					starterblogField.initConditional(content, fv);
				}
			});

			$("input, select, textarea", that.container)
				.removeClass("starterblog-input")
				.addClass("starterblog-modal-input change-by-js");
			fieldsArea.on(
				"change data-change",
				"input, select, textarea",
				function() {
					that.get(_.clone(that.config));
				}
			);

			that.container.on("click", ".modal--tab", function() {
				var id = $(this).attr("data-tab") || "";
				$(".modal--tabs .modal--tab", that.container).removeClass(
					"tab--active"
				);
				$(this).addClass("tab--active");
				$(".modal-tab-content", that.container).removeClass(
					"tab--active"
				);
				$(
					".modal-tab-content.modal-tab--" + id,
					that.container
				).addClass("tab--active");
			});
			$(".modal--tabs .modal--tab", that.container)
				.eq(0)
				.trigger("click");

			this.container.slideUp(0);
		},

		close: function() {
			var that = this;
			that.container.slideUp(300, function() {
				that.$el.removeClass("modal--opening");
				that.$el.attr("data-opening", "");
				$(".action--reset", that.$el).hide();
			});
		},

		reset: function() {
			var that = this;
			$(".starterblog-modal-settings", that.$el).remove();
			try {
				var _default = wpcustomize.control(that.controlID).params
					.default;
				that.values = _default;
			} catch (e) {
				that.values = {};
			}
			if (!$(".starterblog-modal-settings", that.$el).length) {
				var $wrap = $($("#tmpl-starterblog-modal-settings").html());
				that.container = $wrap;
				this.$el.append($wrap);
				that.addFields();
			} else {
				that.container = $(".starterblog-modal-settings", that.$el);
			}

			that.$el.addClass("starterblog-modal--inside");
			that.$el.addClass("modal--opening");
			that.container.show(0);
			$(".starterblog-hidden-modal-input", that.$el)
				.val(JSON.stringify(that.values))
				.trigger("change");
		},

		get: function(config) {
			var data = {};
			var that = this;
			that.config = config;
			_.each(that.config.tabs, function(label, key) {
				var subdata = {};
				var content = $(
					".modal-tab-content.modal-tab--" + key,
					that.container
				);
				if (_.isObject(that.config[key + "_fields"])) {
					_.each(that.config[key + "_fields"], function(f) {
						subdata[f.name] = starterblogField.getValue(
							f,
							$(
								'.starterblog--group-field[data-field-name="' +
									f.name +
									'"]',
								content
							)
						);
					});
				}
				data[key] = subdata;
				starterblogField.initConditional(content, subdata);
			});
			$(".starterblog-hidden-modal-input", this.$el)
				.val(JSON.stringify(data))
				.trigger("change");
			return data;
		},

		open: function() {
			var that = this;
			var status = that.$el.attr("data-opening") || false;
			if (status !== "opening") {
				that.$el.attr("data-opening", "opening");
				that.values = $(
					".starterblog-hidden-modal-input",
					that.$el
				).val();
				try {
					that.values = JSON.parse(that.values);
				} catch (e) {}
				that.$el.addClass("starterblog-modal--inside");
				if (!$(".starterblog-modal-settings", that.$el).length) {
					var $wrap = $($("#tmpl-starterblog-modal-settings").html());
					$wrap.hide();
					that.container = $wrap;
					that.$el.append($wrap);
					that.addFields();
				} else {
					that.container = $(".starterblog-modal-settings", that.$el);
				}

				this.container.slideDown(300);
				this.$el.addClass("modal--opening");
				$(".action--reset", this.$el).show();
			} else {
				this.container.slideUp(300, function() {
					that.$el.attr("data-opening", "");
					$(".starterblog-modal-settings", that.$el).hide();
					that.$el.removeClass("modal--opening");
					$(".action--reset", that.$el).hide();
				});
			}
		}
	};

	var initModalControls = {};
	var initModal = function() {
		$document.on(
			"click",
			".customize-control-starterblog-modal .action--edit, .customize-control-starterblog-modal .action--reset, .customize-control-starterblog-modal .starterblog-control-field-header",
			function(e) {
				e.preventDefault();
				var controlID = $(this).attr("data-control") || "";
				if (_.isUndefined(initModalControls[controlID])) {
					var c = wpcustomize.control(controlID);
					if (controlID && !_.isUndefined(c)) {
						var m = _.clone(starterblogModal);
						m.config = c.params.fields;
						m.$el = $(this)
							.closest(".customize-control-starterblog-modal")
							.eq(0);
						m.controlID = controlID;
						initModalControls[controlID] = m;
					}
				}

				if (!_.isUndefined(initModalControls[controlID])) {
					if ($(this).hasClass("action--reset")) {
						initModalControls[controlID].reset();
					} else {
						initModalControls[controlID].open();
					}
				}
			}
		);
	};

	//---------------------------------------------------------------------------
	var starterblogStyling = {
		tabs: {
			normal: "Normal",
			hover: "Hover"
		},
		fields: {},
		normal_fields: {},
		hover_fields: {},
		controlID: "",
		$el: "",
		contailner: "",
		setupFields: function(fields, list) {
			var newfs;
			var i;
			var newList = [];
			if (fields === -1) {
				newList = list;
			} else if (fields === false) {
				newList = null;
			} else {
				if (_.isObject(fields)) {
					newfs = {};
					i = 0;
					_.each(list, function(f) {
						if (_.isUndefined(fields[f.name]) || fields[f.name]) {
							newfs[i] = f;
							i++;
						}
					});

					newList = newfs;
				}
			}
			return newList;
		},
		setupConfig: function(tabs, normal_fields, hover_fields) {
			var that = this;
			that.tabs = {};
			that.normal_fields = {};
			that.hover_fields = {};

			that.tabs = _.clone(StarterBlog_Control_Args.styling_config.tabs);
			if (tabs === false) {
				that.tabs["hover"] = false;
			} else if (_.isObject(tabs)) {
				that.tabs = tabs;
			}

			that.normal_fields = that.setupFields(
				normal_fields,
				StarterBlog_Control_Args.styling_config.normal_fields
			);
			that.hover_fields = that.setupFields(
				hover_fields,
				StarterBlog_Control_Args.styling_config.hover_fields
			);
		},
		addFields: function(values) {
			var that = this;
			if (!_.isObject(that.values)) {
				that.values = {};
			}
			that.values = _.defaults(that.values, {
				hover: {},
				normal: {}
			});
			var fieldsArea = $(
				".starterblog-modal-settings--fields",
				that.container
			);
			fieldsArea.html("");

			var tabsHTML = $('<div class="modal--tabs"></div>');
			var c = 0;
			_.each(that.tabs, function(label, key) {
				if (label && !_.isEmpty(that[key + "_fields"])) {
					c++;
					tabsHTML.append(
						'<div><span data-tab="' +
							key +
							'" class="modal--tab modal-tab--' +
							key +
							'">' +
							label +
							"</span></div>"
					);
				}
			});

			fieldsArea.append(tabsHTML);
			if (c <= 1) {
				tabsHTML.addClass("starterblog--hide");
			}
			starterblogField.devices = StarterBlog_Control_Args.devices;
			_.each(that.tabs, function(label, key) {
				if (
					_.isObject(that[key + "_fields"]) &&
					!_.isEmpty(key + "_fields")
				) {
					var content = $(
						'<div class="modal-tab-content modal-tab--' +
							key +
							'"></div>'
					);
					fieldsArea.append(content);
					starterblogField.addFields(
						that[key + "_fields"],
						that.values[key],
						content,
						function() {
							that.get();
						}
					);
					starterblogField.initConditional(content, that.values[key]);
				}
			});

			$("input, select, textarea", that.container)
				.removeClass("starterblog-input")
				.addClass("starterblog-modal-input change-by-js");

			fieldsArea.on(
				"change data-change",
				"input, select, textarea",
				function() {
					that.get();
				}
			);

			that.container.on("click", ".modal--tab", function() {
				var id = $(this).attr("data-tab") || "";
				$(".modal--tabs .modal--tab", that.container).removeClass(
					"tab--active"
				);
				$(this).addClass("tab--active");
				$(".modal-tab-content", that.container).removeClass(
					"tab--active"
				);
				$(
					".modal-tab-content.modal-tab--" + id,
					that.container
				).addClass("tab--active");
			});
			$(".modal--tabs .modal--tab", that.container)
				.eq(0)
				.trigger("click");

			this.container.slideUp(0);
		},

		close: function() {
			var that = this;
			that.container.slideUp(300, function() {
				that.$el.removeClass("modal--opening");
				that.$el.attr("data-opening", "");
				$(".action--reset", that.$el).hide();
			});
		},

		reset: function() {
			var that = this;

			$(".starterblog-modal-settings", that.$el).remove();
			try {
				var _default = wpcustomize.control(that.controlID).params
					.default;
				that.values = _default;
			} catch (e) {
				that.values = {};
			}
			if (!$(".starterblog-modal-settings", that.$el).length) {
				var $wrap = $($("#tmpl-starterblog-modal-settings").html());
				that.container = $wrap;
				that.$el.append($wrap);
				that.addFields();
			} else {
				that.container = $(".starterblog-modal-settings", that.$el);
			}

			that.$el.addClass("starterblog-modal--inside");
			that.$el.addClass("modal--opening");
			that.container.show(0);
			$(".starterblog-hidden-modal-input", that.$el)
				.val(JSON.stringify(that.values))
				.trigger("change");
		},

		get: function() {
			var data = {};
			var that = this;
			_.each(that.tabs, function(label, key) {
				var subdata = {};
				var content = $(
					".modal-tab-content.modal-tab--" + key,
					that.container
				);
				if (_.isObject(that[key + "_fields"])) {
					_.each(that[key + "_fields"], function(f) {
						subdata[f.name] = starterblogField.getValue(
							f,
							$(
								'.starterblog--group-field[data-field-name="' +
									f.name +
									'"]',
								content
							)
						);
					});
				}
				data[key] = subdata;
				starterblogField.initConditional(content, subdata);
			});

			$(".starterblog-hidden-modal-input", this.$el)
				.val(JSON.stringify(data))
				.trigger("change");
			return data;
		},

		open: function() {
			var that = this;
			var status = that.$el.attr("data-opening") || false;
			if (status !== "opening") {
				that.$el.attr("data-opening", "opening");

				that.values = $(
					".starterblog-hidden-modal-input",
					that.$el
				).val();
				try {
					that.values = JSON.parse(that.values);
				} catch (e) {}
				that.$el.addClass("starterblog-modal--inside");
				if (!$(".starterblog-modal-settings", that.$el).length) {
					var $wrap = $($("#tmpl-starterblog-modal-settings").html());
					$wrap.hide();
					that.container = $wrap;
					that.$el.append($wrap);
					that.addFields();
				} else {
					that.container = $(".starterblog-modal-settings", that.$el);
				}

				this.container.slideDown(300);
				that.$el.addClass("modal--opening");
				$(".action--reset", that.$el).show();
			} else {
				that.container.slideUp(300, function() {
					that.$el.attr("data-opening", "");
					$(".starterblog-modal-settings", that.$el).hide();
					that.$el.removeClass("modal--opening");
					$(".action--reset", that.$el).hide();
				});
			}
		}
	};

	var initStylingControls = {};
	var initStyling = function() {
		$document.on(
			"click",
			".customize-control-starterblog-styling .action--edit, .customize-control-starterblog-styling .action--reset",
			function(e) {
				e.preventDefault();
				var controlID = $(this).attr("data-control") || "";
				if (_.isUndefined(initStylingControls[controlID])) {
					var c = wpcustomize.control(controlID);
					var s = _.clone(starterblogStyling);
					var tabs = null,
						normal_fields = -1,
						hover_fields = -1;
					if (controlID && !_.isUndefined(c)) {
						if (
							!_.isUndefined(c.params.fields) &&
							_.isObject(c.params.fields)
						) {
							if (!_.isUndefined(c.params.fields.tabs)) {
								tabs = c.params.fields.tabs;
							}
							if (!_.isUndefined(c.params.fields.normal_fields)) {
								normal_fields = c.params.fields.normal_fields;
							}
							if (!_.isUndefined(c.params.fields.hover_fields)) {
								hover_fields = c.params.fields.hover_fields;
							}
						}
					}
					s.$el = $(this)
						.closest(".customize-control-starterblog-styling")
						.eq(0);
					s.setupConfig(tabs, normal_fields, hover_fields);
					s.controlID = controlID;
					initStylingControls[controlID] = s;
				}

				if (!_.isUndefined(initStylingControls[controlID])) {
					if ($(this).hasClass("action--reset")) {
						initStylingControls[controlID].reset();
					} else {
						initStylingControls[controlID].open();
					}
				}
			}
		);
	};

	//---------------------------------------------------------------------------

	wpcustomize.bind("ready", function(e, b) {
		$document.on("starterblog/customizer/device/change", function(e, device) {
			$(".starterblog--device-select a").removeClass("starterblog--active");
			if (device != "mobile") {
				$(".starterblog--device-mobile").addClass("starterblog--hide");
				$(".starterblog--device-general").removeClass("starterblog--hide");
				$(".starterblog--tab-device-general").addClass(
					"starterblog--active"
				);
			} else {
				$(".starterblog--device-general").addClass("starterblog--hide");
				$(".starterblog--device-mobile").removeClass("starterblog--hide");
				$(".starterblog--tab-device-mobile").addClass(
					"starterblog--active"
				);
			}
		});

		$document.on("click", ".starterblog--tab-device-mobile", function(e) {
			e.preventDefault();
			$document.trigger("starterblog/customizer/device/change", ["mobile"]);
		});

		$document.on("click", ".starterblog--tab-device-general", function(e) {
			e.preventDefault();
			$document.trigger("starterblog/customizer/device/change", [
				"general"
			]);
		});

		$(".accordion-section").each(function() {
			var s = $(this);
			var t = $(".starterblog--device-select", s).first();
			$(".customize-section-title", s).append(t);
		});

		// Devices Switcher
		$document.on("click", ".starterblog-devices button", function(e) {
			e.preventDefault();
			var device = $(this).attr("data-device") || "";
			//console.log('Device', device);
			$(
				'#customize-footer-actions .devices button[data-device="' +
					device +
					'"]'
			).trigger("click");
		});

		// Devices Switcher
		$document.on("change", ".starterblog--field input:checkbox", function(e) {
			if ($(this).is(":checked")) {
				$(this)
					.parent()
					.addClass("starterblog--checked");
			} else {
				$(this)
					.parent()
					.removeClass("starterblog--checked");
			}
		});

		// Setup conditional
		var ControlConditional = function(decodeValue) {
			if (_.isUndefined(decodeValue)) {
				decodeValue = false;
			}
			var allValues = wpcustomize.get();
			// console.log( 'ALL Control Values', allValues );
			_.each(allValues, function(value, id) {
				var control = wpcustomize.control(id);
				if (!_.isUndefined(control)) {
					if (control.params.type == "starterblog") {
						if (!_.isEmpty(control.params.required)) {
							var check = false;
							check = control.multiple_compare(
								control.params.required,
								allValues,
								decodeValue
							);
							if (!check) {
								control.container.addClass("starterblog--hide");
							} else {
								control.container.removeClass(
									"starterblog--hide"
								);
							}
						}
					}
				}
			});
		};

		$document.ready(function() {
			_.each(starterblog_controls_list, function(c, k) {
				new starterblog_control(c);
			});

			ControlConditional(false);
			$document.on("starterblog/customizer/value_changed", function() {
				ControlConditional(true);
			});

			IconPicker.init();
			FontSelector.init();
			initStyling();
			initModal();
			intTypos();
		});

		// Add reset button to sections
		wpcustomize.section.each(function(section) {
			if (
				section.params.type == "section" ||
				section.params.type == "starterblog_section"
			) {
				section.container
					.find(
						".customize-section-description-container .customize-section-title"
					)
					.append(
						'<button data-section="' +
							section.id +
							'" type="button" title="' +
							StarterBlog_Control_Args.reset +
							'" class="customize--reset-section" aria-expanded="false"><span class="screen-reader-text">' +
							StarterBlog_Control_Args.reset +
							"</span></button>"
					);
			}
		});

		// Remove checked align
		$document.on("dblclick", ".starterblog-text-align label", function(e) {
			var input = $(this).find('input[type="radio"]');
			if (input.length) {
				if (input.is(":checked")) {
					input.removeAttr("checked");
					input.trigger("data-change");
				}
			}
		});

		$document.on("click", ".customize--reset-section", function(e) {
			e.preventDefault();
			if ($(this).hasClass("loading")) {
				return;
			}

			if (!confirm(StarterBlog_Control_Args.confirm_reset)) {
				return;
			}

			$(this).addClass("loading");
			var section = $(this).attr("data-section") || "";
			var urlParser = _.clone(window.location);

			if (section) {
				var setting_keys = [];
				var controls = wp.customize.section(section).controls();
				_.each(controls, function(c, index) {
					wpcustomize(c.id).set("");
					setting_keys[index] = c.id;
				});

				$.post(
					ajaxurl,
					{
						action: "starterblog__reset_section",
						section: section,
						settings: setting_keys
					},
					function() {
						$(window).off("beforeunload.customize-confirm");
						top.location.href =
							urlParser.origin +
							urlParser.pathname +
							"?autofocus[section]=" +
							section +
							"&url=" +
							encodeURIComponent(
								wpcustomize.previewer.previewUrl.get()
							);
					}
				);
			}
		});

		/**
		 * Image Select disable click
		 */
		$document.on("click", ".starterblog-radio-list p", function(e) {
			var id =
				$(this)
					.find("input")
					.attr("data-name") || false;
			var disabled = $(this).hasClass("input-disabled");

			if (id) {
				var setting = wp.customize(id);
				var control = wp.customize.control(id);
				var code = "noti_" + id;
				var msg = "";
				if (control.params._pro && control.params.disabled_pro_msg) {
					msg = control.params.disabled_pro_msg;
				} else if (control.params.disabled_msg) {
					msg = control.params.disabled_msg;
				}
				if (msg) {
					if (disabled) {
						setting.notifications.add(
							code,
							new wp.customize.Notification(code, {
								type: "warning",
								message: msg
							})
						);
					} else {
						setting.notifications.remove(code);
					}
				}
			}
		});

		/**
		 * When panel open
		 */
		_.each(StarterBlog_Control_Args.panel_urls, function(url, id) {
			if (url) {
				wp.customize.panel(id, function(panel) {
					panel.expanded.bind(function(isExpanded) {
						if (isExpanded) {
							wp.customize.previewer.previewUrl.set(url);
						}
					});
				});
			}
		});

		_.each(StarterBlog_Control_Args.section_urls, function(url, id) {
			if (url) {
				wp.customize.section(id, function(section) {
					section.expanded.bind(function(isExpanded) {
						if (isExpanded) {
							wp.customize.previewer.previewUrl.set(url);
						}
					});
				});
			}
		});
	}); // end customize ready
})(jQuery, wp.customize || null);
