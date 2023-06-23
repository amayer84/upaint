$(function() {

    function getCookie(name) {
        var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
        return v ? v[2] : null;
    }

    function setCookie(name, value, days) {
        var d = new Date;
        d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
        document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
    }

    function deleteCookie(name) {
        setCookie(name, '', -1);
    }

    function showSubmitButtonOrInfoitems(plugin) {

        var mixed_mode = $(plugin).find(".upaint_plugin_mixed_mode");

        if (mixed_mode.length) {
            /* plugin is configured with detail page */
            var showSubmit = true;

            $(plugin).find(".upaint_select").each(function(a) {
                if (!($(this).find("option:selected").val() > 0)) {
                    showSubmit = false;
                }
            });

            if (showSubmit) {
                $(plugin).find(".upaint_submit").removeClass("d-none");
            } else {
                $(plugin).find(".upaint_submit").addClass("d-none");
            }
        } else {
            var auto_eval = true;

            $(plugin).find(".upaint_select").each(function(a) {
                if (!($(this).find("option:selected").val() > 0)) {
                    auto_eval = false;
                }
            });

            if (auto_eval) {
                showInfoitems($(plugin));
            }
        }
    }

    function loadInfoItem(container, uid) {
        var jqxhr = $.ajax( "/index.php?id=1&no_cache=1&type=20211130&tx_upaint_ajaxhandler[uid]=" + uid )
            .done(function(result) {
                $(result).appendTo(container);
            });
    }

    function showInfoitems(plugin) {
        /* clean dropzone */
        $(plugin).find(".upaint_infoitem_dropzone").empty();

        /* save answers in cookies */
        $(plugin).find(".upaint_select").each(function(a) {
            var question_uid = $(this).data("uid");

            var answer = $(this).find("option:selected").val();

            if (answer > 0) {
                setCookie("upaint_question_" + question_uid, answer, 365);
            } else {
                deleteCookie("upaint_question_" + question_uid);
            }
        });

        /* determine plugin uid */
        var pluginuid = $(plugin).data("pluginuid");
        
        $(plugin).find(".upaint_infoitem_pool .upaint_infoitem_pool_item").each(function(a) {
            var infoitem_uid = $(this).data("uid");
            var show_infoitem = false;
            var infoitem_found = false;
            var current_infoitem = $(this);

            var dropzone = $(this).closest(".upaint_container").find(".upaint_infoitem_dropzone");

            /* check if infoitem can be shown */
            $(this).closest(".upaint_container").find(".upaint_answerconfig").each(function(b) {
                if ($(this).data("infoitems")) {
                    var infoitems = String($(this).data("infoitems")).split(',');

                    for (var i = 0; i < infoitems.length; i++) {
                        if (infoitems[i] == infoitem_uid) {
                            infoitem_found = true;

                            var node_uid = $(this).data("node");
                            var answer_uid = $(this).data("answer");

                            /* check which question/answers combination is necessary to show this infoitem */
                            var given_answer = $(this).closest(".upaint_container").find(".upaint_formgroup_" + node_uid + " .upaint_select").val();

                            if (given_answer == answer_uid) {
                                show_infoitem = true;
                            }
                        }
                    }
                }
            });

            if (show_infoitem && infoitem_found) {
                // $(current_infoitem).clone().appendTo(dropzone);
                var container = $('<div class="upaint_infoitem_container"></div>');
                $(container).appendTo(dropzone);
                loadInfoItem(container, $(current_infoitem).data("uid"));
            }
        });
    }

    function saveSession(plugin) {
        var infoitem_list = "";

        /* check if given answer config is among answered questions */
        $(plugin).find(".upaint_formgroup").each(function(a) {
            
            var node = $(this).find("select").data("node");
            var answer = $(this).find("option:selected").val();

            var infoitems = $(plugin).find(".upaint_answerconfig_node_" + node + "_" + answer).data("infoitems");

            if (infoitems) {
                var infoitems_array = String(infoitems).split(',');

                for (var i = 0; i < infoitems_array.length; i++) {
                    if (infoitem_list) {
                        infoitem_list = infoitem_list + "," + infoitems_array[i];
                    } else {
                        infoitem_list = infoitems_array[i];
                    }
                }
            } 
        });

        setCookie("upaint_infoitem_list", infoitem_list, 365);
    }

    function showNode(plugin, node_uid, rootline, element) {

        /* get node by uid */
        var node = $(plugin).find(".upaint_node_" + node_uid);

        /* get question */
        var question = $(plugin).find(".upaint_question_" + $(node).data("question"));

        /* get all possible answers for this questions */
        var answerconfigs = $(plugin).find(".upaint_answerconfig_node_" + node_uid);

        if ($('html').attr('lang') == 'de') {
            var lang_text = 'Bitte auswählen';
        } else {
            var lang_text = 'Please select';
        }

        var form_element =
            '<div class="form-group upaint_formgroup upaint_formgroup_' + node_uid + '" data-rootline="' + rootline + '">' +
            '   <label for="upaint_node_' + node_uid + '">' + $(question).data("title") + '</label>' +
            '   <select class="form-control upaint_select" id="upaint_node_' + node_uid + '" data-uid="' + $(question).data("uid") + '" data-node="' + node_uid + '">' +
            '   <option>' + lang_text + '</option>';

        var selected = getCookie("upaint_question_" + $(question).data("uid"))

        var auto_nextnode = -1;

        $(answerconfigs).each(function(a) {
            /* get answer */
            var answer = $(plugin).find(".upaint_answer_" + $(this).data("answer"));

            /* check if answer is saved in session */
            if (selected == $(answer).data("uid")) {
                form_element += '<option value="' + $(answer).data("uid") + '" selected>' + $(answer).data("title") + '</option>';

                auto_nextnode = $(".upaint_answerconfig_node_" + node_uid + "_" + $(answer).data("uid")).data("nextnode");
            } else {
                form_element += '<option value="' + $(answer).data("uid") + '">' + $(answer).data("title") + '</option>';
            }
        });

        form_element +=
            '   </select>' +
            '</div>';

        if (element) {
            $(form_element).insertAfter(element);
        } else {
            $(plugin).find(".upaint_formstage").append(form_element);
        }

        if (auto_nextnode > 0) {
            if (rootline) {
                rootline = rootline + "," + node_uid;
            } else {
                rootline = node_uid;
            }

            /* find new element */
            var element = $(plugin).find(".upaint_formgroup_" + node_uid);

            showNode(plugin, auto_nextnode, rootline, element);
        }
    }

    /* get every container of current page and create form */
    $(".upaint_container").each(function(a) {
        var plugin = $(this);

        /* try to find a form stage */
        if ($(this).find(".upaint_formstage").length) {
            /* extract start nodes */
            $(this).find(".upaint_startnode").each(function(b) {
                showNode(plugin, $(this).data("uid"), "");
            });
        }

        showSubmitButtonOrInfoitems(plugin);
    });

    $(".upaint_container").on("change", "select", function() {
        /* try to extraxt the next node */
        var node_uid = $(this).data("node");
        var answer_uid = $(this).find("option:selected").val();

        var node = $(".upaint_answerconfig_node_" + node_uid + "_" + answer_uid).data("nextnode");

        var plugin = $(this).closest(".upaint_container");

        $(plugin).find(".form-group").each(function(a) {
            var rootline = String($(this).data("rootline"));

            if (rootline) {
                var node_uids = rootline.split(',');

                for (var i = 0; i < node_uids.length; i++) {
                    if (node_uids[i] == node_uid) {
                        $(this).remove();
                        break;
                    }
                }
            }
        });

        if (node > 0) {
            /* get rootline */
            var rootline = $(this).closest(".form-group").data("rootline");

            if (rootline) {
                rootline = rootline + "," + node_uid;
            } else {
                rootline = node_uid;
            }

            /* find closest element */
            var element = $(this).closest(".upaint_formgroup");

            showNode(plugin, node, rootline, element);
        }

        showSubmitButtonOrInfoitems(plugin);
    });

    $(".upaint_container").on("click", ".upaint_submit", function() {
        var link = $(this).closest(".upaint_container").find(".upaint_single_uri").data('uri');
        
        var plugin =  $(this).closest(".upaint_container");
        saveSession(plugin);

        var questionList = "";

        $(this).closest(".upaint_container").find(".upaint_select").each(function(a) {
            var question_uid = $(this).data("uid");

            if (questionList === "") {
                questionList = question_uid;
            } else {
                questionList = questionList + ',' + question_uid;
            }

            var answer = $(this).find("option:selected").val();

            if (answer > 0) {
                setCookie("upaint_question_" + question_uid, answer, 365);
            } else {
                deleteCookie("upaint_question_" + question_uid);
            }
        });

        var pluginId = $(this).closest(".upaint_container").data("pluginuid");

        setCookie("upaint_plugin_" + pluginId, questionList, 365);

        window.location.href = link;
    });
});