+function ($) {
    'use strict';

    var modals = $('.modal.multi-step');

    modals.each(function (idx, modal) {
        var $modal = $(modal);
        var depth = parseInt($modal.attr('data-multi-step-depth'));
        var start = $modal.attr('data-multi-step-start');

        var route = [];

        function reset() {
            $modal.find("[class*='step-']").hide();
        }

        function getAllCurentStep(step) {
            var rest = step.split("-");
            var all = [];

            function create(pos, list) {
                var result = 'step';
                for (var i = 0; i <= pos; i++) {
                    result += '-' + list[i];
                }
                return result;
            }

            for (var i = 0; i < rest.length; i++) {
                all.push(create(i, rest));
            }
            return all;
        }

        function getAllCurentStepInline(list) {
            var result = '';
            list.forEach(function (y, x) {
                    if (x != 0)
                        result += ' ,'
                    result += '.' + y;
                }
            );
            return result;

        }

        function goToPrev() {
            if(route.length > 1) {
                route.pop();
                goToStep(route.pop());
            }
        }

        function goToStep(step) {
            reset();
            route.push(step);
            var curentstep = getAllCurentStep(step);
            var to_show = $modal.find(getAllCurentStepInline(curentstep));
            if (to_show.length === 0) {
                return;
            }
            to_show.show();
            $modal.removeClass(function (index, css) {
                return (css.match(/(^|\s)current-step-\S+/g) || []).join(' ');
            });
            curentstep.forEach(function (y) {
                $modal.addClass('current-' + y)
            });
            var current = parseInt(step, 10);
            //findFirstFocusableInput(to_show).focus();
        }

        function findFirstFocusableInput(parent) {
            var candidates = [parent.find('input'), parent.find('select'),
                    parent.find('textarea'), parent.find('button')],
                winner = parent;
            $.each(candidates, function () {
                if (this.length > 0) {
                    winner = this[0];
                    return false;
                }
            });
            return $(winner);
        }

        function bindEventsToModal($modal) {
            $modal.find('[data-multi-step-target]').each(function () {
                $(this).click(function () {
                    var step_target = $(this).attr("data-multi-step-target");
                    if(step_target == "prev") {
                        goToPrev();
                    } else {
                        goToStep(step_target);
                    }
                });
            });

        }

        function bindEventToCloseModal($modal) {
            $modal.on('hidden.bs.modal', function () {
                softInitialize();
            })
        }

        function softInitialize() {
            route = [];
            var init_step = start;
            if (init_step == null) {
                init_step = '1';
                for (var i = 1; i < depth; i++) {
                    init_step += '-1';
                }
            }
            goToStep(init_step);
        }

        function bindEventListener($modal) {
            $modal.find('[data-multi-step-listener]').each(function () {
                var input = $(this);
                input.change(function () {
                    var value = this.value;
                    var changeId = input.attr("data-multi-step-listener");
                    $modal.find(changeId).each(function () {
                        $(this).attr("data-multi-step-target", value);
                    });
                });
            });

            $modal.on('multiStepGoTo', function(e, step) {
                goToStep(step);
            });
        }

        function initialize() {
            softInitialize();
            bindEventsToModal($modal);
            bindEventToCloseModal($modal);
            bindEventListener($modal);
        }

        initialize();
    })
}(jQuery);
