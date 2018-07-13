let Survey = require('survey-jquery');

(function () {
    let displayQuestionnaire = function () {
        let wrapperId = 'questionnaire-display-section';
        let wrapper = $('#' + wrapperId);
        let json = wrapper.data('content');
        Survey.StylesManager.applyTheme("darkblue");
        Survey.surveyStrings.emptySurvey = "There is not currently an active survey.";
        Survey.surveyStrings.loadingSurvey = "Please wait. The survey is loading…";
        let survey = new Survey.Model(json);
        survey
            .onComplete
            .add(function (result) {
                console.log("result: ", JSON.stringify(result.data));
            });
        new Survey.Survey(JSON.stringify(json), wrapperId);
    };

    let init = function () {
        displayQuestionnaire();
    };

    init();
})();