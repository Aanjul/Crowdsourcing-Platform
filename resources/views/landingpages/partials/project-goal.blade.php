<div class="activity-title wrapper-title">
    <p>Target: {{$projectGoalVM->goal}} responses</p>
</div>
<div class="progress-container">
    <div id="progress-bar-circle"
         data-target="{{$projectGoalVM->targetAchievedPercentage}}">
    </div>
</div>
@push('scripts')
    <script src="{{ mix('dist/js/projectGoal.js')}}?{{env("APP_VERSION")}}"></script>
@endpush