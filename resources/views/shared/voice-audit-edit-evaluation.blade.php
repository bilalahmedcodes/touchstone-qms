
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Percentage</label>
            <input type="text" name="percentage" value="{{ $voice_audit->percentage }}" id="percentage"
                class="form-control percentage" readonly>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Customer Name <span>*</span></label>
            <input type="hidden" class="form-control" name="customer_name" value="{{ $voice_audit->customer_name }}"
                required>
            <input type="text" name="customer_name" value="{{ $voice_audit->customer_name }}" class="form-control"
                disabled>
        </div>
        @error('customer_name')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Customer Phone <span>*</span></label>
            <input type="hidden" class="form-control" name="customer_phone" value="{{ $voice_audit->customer_phone }}"
                required>
            <input type="text" class="form-control" value="{{ $voice_audit->customer_phone }}"
                placeholder="Enter Customer Phone" disabled>
        </div>
        @error('customer_phone')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Recording Duration <span>*</span></label>
            <input type="text" name="recording_duration" value="{{ $voice_audit->recording_duration }}"
                placeholder="HH:MM:SS" id="duration" class="form-control" required>
        </div>
        @error('recording_duration')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Recording Link</label>
            <input type="text" name="recording_link" value="{{ $voice_audit->recording_link }}" class="form-control">
        </div>
        @error('recording_link')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="">Notes <span>*</span></label>
            <textarea name="notes" rows="3" class="form-control" required>{{ $voice_audit->notes }}</textarea>
        </div>
        @error('notes')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Outcome <span>*</span></label>
            <select name="outcome" class="form-control select2" required>
                <option value="">Select Option</option>
                <option value="accepted" @if ($voice_audit->outcome == 'accepted') selected @endif>Billable</option>
                <option value="rejected" @if ($voice_audit->outcome == 'rejected') selected @endif>Non Billable</option>
            </select>
        </div>
        @error('outcome')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Agent Outcome <span>*</span></label>
            <select name="agent_outcome" class="form-control select2" required>
                <option value="">Select Option</option>
                <option value="accepted" @if ($voice_audit->agent_outcome == 'accepted') selected @endif>Accepted</option>
                <option value="rejected" @if ($voice_audit->agent_outcome == 'rejected') selected @endif>Rejected</option>
            </select>
        </div>
        {{-- @error('outcome')
            <div class="validate-error">{{ $message }}</div>
        @enderror --}}
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Call Type <span>*</span></label>
            <select name="call_type" class="form-control select2" required>
                <option value="">Select Option</option>
                <option value="general"@if ($voice_audit->call_type == 'general') selected @endif>General</option>
                <option value="sales" @if ($voice_audit->call_type == 'sales') selected @endif>Sales</option>
            </select>
        </div>
        {{-- @error('outcome')
            <div class="validate-error">{{ $message }}</div>
        @enderror --}}
    </div>
</div>
<div class="col-md-12">
    <div class="custom-control custom-switch custom-switch-md mt-3">
        <input type="checkbox" value="1" name="review_priority" class="custom-control-input review"
            id="customSwitch1" @if ($voice_audit->review_priority == 1) checked @endif onchange="setFatalValue(this)">
        <label class="custom-control-label" for="customSwitch1">Send Critical Alert / Fatal</label>
    </div>
</div>
</div>
