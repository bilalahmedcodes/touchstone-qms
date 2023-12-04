<div class="card-body">
    <div class="col-sm-6">
        <!-- checkbox -->
        <div class="form-group clearfix">
            <div class="icheck-success d-inline">
                <input type="radio" id="good" @if ($voice_audit->rating == 'good') checked @endif value="good"
                    name="rating" onclick="myFunction()">
                <label for="good">
                    Good
                </label>
                <span>(100)</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <!-- radio -->
        <div class="form-group clearfix">
            <div class="icheck-primary d-inline">
                <input type="radio" id="aboveAverage" @if ($voice_audit->rating == 'above average') checked @endif
                    value="above average" name="rating" onclick="myFunction()">
                <label for="aboveAverage">
                    Above Average
                </label>
                <span>(95)</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <!-- radio -->
        <div class="form-group clearfix">
            <div class="icheck-info d-inline">
                <input type="radio" id="average" @if ($voice_audit->rating == 'average') checked @endif value="average"
                    name="rating" onclick="myFunction()">
                <label for="average">
                    Average
                </label>
                <span>(80)</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <!-- radio -->
        <div class="form-group clearfix">
            <div class="icheck-warning d-inline">
                <input type="radio" id="bad" @if ($voice_audit->rating == 'bad') checked @endif value="bad"
                    name="rating" onclick="myFunction()">
                <label for="bad">
                    Bad
                </label>
                <span>(50)</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <!-- radio -->
        <div class="form-group clearfix">
            <div class="icheck-danger d-inline">
                <input type="radio" id="fatal" @if ($voice_audit->rating == 'fatal') checked @endif value="fatal"
                    name="rating" onclick="myFunction()">
                <label for="fatal">
                    Fatal
                </label>
                <span>(0)</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <!-- radio -->
        <div class="form-group clearfix">
            <div class="icheck-danger d-inline">
                <input type="radio" id="other" @if ($voice_audit->rating == 'other') checked @endif value="other"
                    name="rating" onclick="myFunction()">
                <label for="other">
                    Other
                </label>
            </div>
        </div>
    </div>
</div>
