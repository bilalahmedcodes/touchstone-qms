<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Record ID <span>*</span></label>
            <input type="hidden" class="form-control" name="record_id" value="{{ $voice_audit->record_id }}" required>
            <input type="text" class="form-control" name="" value="{{ $voice_audit->record_id }}" disabled>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Campaign Name</label>
            <input type="hidden" class="form-control" name="campaign_id" value="{{ $voice_audit->campaign_id }}"
                required>
            <input type="text" class="form-control" id="campaign" value="{{ $voice_audit->campaign->name ?? '' }}"
                name="" disabled>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Client</label>
            <input type="hidden" name="project_id" value="{{ $voice_audit->project_id }}">
            <input type="text" class="form-control" value="{{ $voice_audit->project->name ?? '' }}" disabled>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Call Date <span>*</span></label>
            <input type="hidden" class="form-control" name="call_date"
                value="{{ date('Y-m-d', strtotime($voice_audit->call_date)) }}" required>
            <input type="text" class="form-control" value="{{ $voice_audit->call_date }}" name="call_date" disabled>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">HRMS ID <span>*</span></label>
            <input type="text" class="form-control" value="{{ $voice_audit->associate->hrms_id ?? '' }}" disabled>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Agent Name<span>*</span></label>
            <input type="hidden" class="form-control" name="associate_id" value="{{ $voice_audit->associate_id }}">
            <input type="text" class="form-control" id="reporting" name=""
                value="{{ $voice_audit->associate->name }}" disabled>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Reporting To<span>*</span></label>
            <input type="hidden" class="form-control" name="team_lead_id"
                value="{{ $voice_audit->team_lead_id ?? 0 }}">
            <input type="text" class="form-control" id="reporting" name=""
                value="{{ $voice_audit->teamLead->name ?? '' }}" disabled>
        </div>
    </div>
</div>
