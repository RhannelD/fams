<div>
    @isset($agreement)
    <div class="card mb-3 shadow requirement-item-hover">
        <div class="card-body">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" 
                            wire:click='toggle_check'
                            @if ($agreement->response_agreements->count())
                                checked
                            @endif
                            >
                    </div>
                </div>
                <div class="form-control text-nowrap overflow-auto">
                    I agree with the 
                    <a data-toggle="collapse" href="#agreement-collapse" role="button" aria-expanded="false" aria-controls="agreement-collapse">
                        Terms and Conditions
                    </a>
                </div>
            </div>
            <div class="collapse" id="agreement-collapse">
                <hr class="my-2">
                <p>
                    {!! Purify::clean($agreement->agreement) !!}
                </p>
            </div>
        </div>
    </div>
    @endisset
</div>
