let Survey = require('survey-jquery');
let ProgressBar = require('progressbar.js');

(function () {
    let survey;

    let displayQuestionnaire = function () {
        let wrapperId = 'questionnaire-display-section';
        let wrapper = $('#' + wrapperId);
        if (wrapper.length > 0) {
            let json = wrapper.data('content');
            Survey.StylesManager.applyTheme("darkblue");
            Survey.surveyStrings.emptySurvey = "There is not currently an active survey.";
            Survey.surveyStrings.loadingSurvey = "Please wait. The survey is loading…";
            survey = new Survey.Survey(JSON.stringify(json), wrapperId);
            survey
                .onComplete
                .add(function (result) {
                    let button = $('.respond-questionnaire').first();
                    let response = JSON.stringify(result.data);
                    let questionnaire_id = button.data('questionnaire-id');
                    let url = button.data('url');
                    $.ajax({
                        method: 'post',
                        data: {questionnaire_id, response},
                        url: url,
                        beforeSend: function () {
                            $("#questionnaire-modal").addClass("loading");
                        },
                        success: function (response) {
                            $("#questionnaire-modal").removeClass('loading');
                            swal({
                                title: "Success!",
                                text: "Your response has been successfully stored.",
                                type: "success",
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                            }, function () {
                                let split = window.location.toString().split("#");
                                window.location = split[0] + "#questionnaire";
                                $("#questionnaire-modal").modal('hide');
                                window.location.reload();
                            });
                        }
                    });
                });
        }
    };

    let displayProgressBar = function () {

        var bar = new ProgressBar.Circle($("#progress-bar-circle")[0], {
            color: '#0063aa',
            // This has to be the same size as the maximum width to
            // prevent clipping
            strokeWidth: 4,
            trailWidth: 2,
            easing: 'easeInOut',
            duration: 1400,
            text: {
                autoStyleContainer: false
            },
            from: {color: '#008AE5', width: 2},
            to: {color: '#008AE5', width: 4},
            // Set default step function for all animate calls
            step: function (state, circle) {
                circle.path.setAttribute('stroke', state.color);
                circle.path.setAttribute('stroke-width', state.width);

                var value = Math.round(circle.value() * 100);
                if (value === 0) {
                    circle.setText('0 %');
                } else {
                    circle.setText(value + "%");
                }

            }
        });

        bar.text.style.fontSize = '2rem';
        bar.animate($("#progress-bar-circle").data("target") / 100);  // Number from 0.0 to 1.0

    };

    let displayTranslation = function () {
        survey.locale = $(this).val();
        survey.render();
    };

    let initEvents = function () {
        $('#questionnaire-lang-selector').on('change', displayTranslation);
    };
    let openQuestionnaireIfNeeded = function () {
        if ($(".respond-questionnaire").first().data("open-on-load") == 1)
            $(".respond-questionnaire").first().trigger("click");

    }
    let init = function () {
        displayQuestionnaire();
        displayProgressBar();
        initEvents();
        openQuestionnaireIfNeeded();
    };

    init();
})();