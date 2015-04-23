/*
 * @author Maleev Artem <maleev777@gmail.com>
 */
(function ($) {
    $.moysklad = {
        form: null,
        container: $("#s-plugins-content"),
        editor: {},
        init: function () {
            this.form = $("#moysklad-form");
        },
        initProductForm:function(){
            this.form = $("#moysklad-product-form");
            var form=this.form;
            var errormsg = form.find(".errormsg");
            errormsg.html('');

            $("#moysklad-product-form input[type='button']").click(function(){
                var btn= $(this);
                $.ajax({
                    url: '?plugin=moysklad&module=product&action=check',
                    data: form.serializeArray(),
                    dataType: "json",
                    type: "post",
                    success: function (response) {
                        btn.removeAttr('disabled');
                        errormsg.html('');
                        if (typeof response.errors !== 'undefined') {
                            if (typeof response.errors.messages !== 'undefined') {
                                $.each(response.errors.messages, function (i, v) {
                                    errormsg.append(v + "<br />");
                                });
                            }
                        } else if (response.status == 'ok' && response.data) {
                            $("#moyskladProduct-tab").attr('data-updated', 1);
                        } else {
                            btn.after("<i class='icon16 no'></i>");
                        }
                    },
                    error: function () {
                        errormsg.text($_('Something wrong'));
                        btn.removeAttr('disabled');
                    }
                });
            });
        },
        initSettings: function () {
            // Сохранение формы настроек
            $("#moysklad-form input[type='submit']").click(function () {
                var btn = $(this);
                var form = btn.closest("form");
                var errormsg = form.find(".errormsg");
                var removeStatusIcon = function(btn) {
                    setTimeout(function() {
                        btn.next(".icon16").remove();
                    }, 3000);
                };
                errormsg.text("");

                btn.next("i.icon16").remove();
                btn.attr('disabled', 'disabled').after("<i class='icon16 loading temp-loader'></i>");

                $.ajax({
                    url: "?plugin=moysklad&module=settings&action=save",
                    data: form.serializeArray(),
                    dataType: "json",
                    type: "post",
                    success: function (response) {
                        btn.removeAttr('disabled').next(".temp-loader").remove();
                        if (typeof response.errors !== 'undefined') {
                            if (typeof response.errors.messages !== 'undefined') {
                                $.each(response.errors.messages, function (i, v) {
                                    errormsg.append(v + "<br />");
                                });
                            }
                        } else if (response.status == 'ok' && response.data) {
                            btn.removeClass("yellow").addClass("green").after("<i class='icon16 yes'></i>");
                            removeStatusIcon(btn);
                        } else {
                            btn.after("<i class='icon16 no'></i>");
                        }
                    },
                    error: function () {
                        errormsg.text($_('Something wrong'));
                        btn.removeAttr('disabled').next(".temp-loader").remove();
                        btn.after("<i class='icon16 no'></i>");
                        removeStatusIcon(btn);
                    }
                });
                return false;
            });

            // Switcher
            $('#moysklad-form .switcher').iButton({
                labelOn: "", labelOff: "", className: 'mini'
            }).change(function () {
                var onLabelSelector = '.' + this.id + '-on-label',
                    offLabelSelector = '.' + this.id + '-off-label';
                var settingsField = $(this).closest('.field-group').next('.field-group');
                if (!this.checked) {
                    if (settingsField.length) {
                        settingsField.hide();
                    }
                    $(onLabelSelector).addClass('unselected');
                    $(offLabelSelector).removeClass('unselected');
                } else {
                    if (settingsField.length) {
                        settingsField.show();
                    }
                    $(onLabelSelector).removeClass('unselected');
                    $(offLabelSelector).addClass('unselected');
                }
            });

        },
        save: function () {
            var form = this.form;
            var btn = $("#s-product-save-button");

            btn.attr('disabled', 'disabled');

            var errormsg = form.find(".errormsg");
            errormsg.html('');

            $.ajax({
                url: form.attr('action'),
                data: form.serializeArray(),
                dataType: "json",
                type: "post",
                success: function (response) {
                    btn.removeAttr('disabled');
                    if (typeof response.errors !== 'undefined') {
                        if (typeof response.errors.messages !== 'undefined') {
                            $.each(response.errors.messages, function (i, v) {
                                errormsg.append(v + "<br />");
                            });
                        }
                    } else if (response.status == 'ok' && response.data) {
                        $("#moyskladProduct-tab").attr('data-updated', 1);
                    } else {
                        btn.after("<i class='icon16 no'></i>");
                    }
                },
                error: function () {
                    errormsg.text($_('Something wrong'));
                    btn.removeAttr('disabled');
                }
            });
            return false;
        }
    };
    //Действие исключительно для вкладки с продуктами
    if (typeof $.product !== 'undefined') {
        $.product.editTabMoyskladProductPluginSave = function () {
            $.moysklad.save();
        };
    }

})(jQuery);