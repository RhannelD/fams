<div>   
@isset($responses)
    <div class="d-flex my-1">
        <div class="ml-0 mr-auto">
            <button class="btn btn-info mx-1 text-white" wire:click="unview_response">
                View Table
            </button>
        </div>
        <div class="my-auto" wire:loading wire:target="change_index">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
        <div class="card mx-1">
            <div class="card-body pb-1 pt-2 px-2">
                {{ $index+1 }} /{{ $responses->count() }}
            </div>
        </div>
        <div class="mr-0">
            @if ( $index > 0 )
                <button class="btn btn-info mx-1 text-white" wire:click="change_index(-1)" wire:loading.attr="disabled">
                    <i class="fas fa-chevron-circle-left"></i> Previous
                </button>
            @else
                <button class="btn btn-dark mx-1" disabled wire:click="change_index(0)">
                    <i class="fas fa-chevron-circle-left"></i> Previous
                </button>
            @endif
    
            @if ( $index < $responses->count()-1 )
                <button class="btn btn-info mx-1 text-white" wire:click="change_index(1)" wire:loading.attr="disabled">
                    Next <i class="fas fa-chevron-circle-right"></i>
                </button>
            @else
                <button class="btn btn-dark mx-1" disabled wire:click="change_index(0)">
                    Next <i class="fas fa-chevron-circle-right"></i>
                </button>
            @endif
        </div>
    </div> 

    <hr class="my-1">
    <div class="table-responsive">
        @isset($responses[$index]->id)
            @php
                $scholar_response = $responses[$index];
            @endphp

            <div class="row">
                <div class="col-sm-12 offset-sm-0 col-md-10 offset-md-1">
                    
                    <div class="card mb-3 mt-2 shadow requirement-item-hover">
                        <div class="card-body">
                            <h4>{{ $scholar_response->user->flname() }}</h4>
                            <table>
                                <tr>
                                    <td>Submitted at:</td>
                                    <td>{{ $scholar_response->submit_at }}</td>
                                </tr>
                                <tr>
                                    <td>Approval:</td>
                                    <td>             
                                        @if ( is_null($scholar_response->approval) )
                                            <span class="badge badge-pill badge-info text-white">Pending</span>
                                        @elseif ($scholar_response->approval)
                                            <span class="badge badge-pill badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">Denied</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @foreach ($scholar_response->requirement->items as $requirement_item)
                        <div class="card mb-3 shadow requirement-item-hover">
                            <div class="card-body">
        
                                <h4>{{ $requirement_item->item }}</h4>
                                @if (!empty($requirement_item->note))
                                    <p>{{ $requirement_item->note }}</p>
                                @endif

                                @switch($requirement_item->type)
                                    @case('file')
                                    @case('cor')
                                    @case('grade')
                                        @php
                                            $response_file = $requirement_item->response_files->where('response_id', $scholar_response->id)->first();
                                        @endphp
                                        @isset($response_file)
                                            <div class="d-flex">
                                                <div class="mr-1 bd-highlight my-0 btn-block">
                                                    <a href="{{ Storage::disk('files')->url($response_file->file_url) }}" target="blank">
                                                        <div class="input-group item-hover">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-primary text-white border-primary">
                                                                    @if ( $response_file->if_file_exist() )
                                                                        @php
                                                                            $file_extension = $response_file->get_file_extension();
                                                                        @endphp
                                                                        @include('livewire.pages.response.response-file-upload-icon-type-livewire')
                                                                    @else
                                                                        <i class="fas fa-exclamation-circle"></i>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <input type="text" class="form-control bg-white border-primary rounded-right mr-1" 
                                                                value="{{ $response_file->file_name }}" readonly>
                                                        </div>
                                                    </a>
                                                </div>
                            
                                                <button class="btn btn-primary rounded" type="button" data-toggle="collapse" 
                                                    data-target="#collapsefile{{ $response_file->id }}" 
                                                    aria-expanded="false" aria-controls="collapsefile{{ $response_file->id }}">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                            </div>
                                            
                                            @if ($file_extension == 'pdf')
                                                <div class="collapse"  id="collapsefile{{ $response_file->id }}">
                                                    <hr class="mb-1 mt-2">
                                                    <iframe src="{{ Storage::disk('files')->url($response_file->file_url) }}" frameborder="0"
                                                        class="btn-block" style="height: 800px;">
                                                    </iframe>
                                                </div>
                                            @endif
                                        @endisset
                                        @break

                                    @case('question')  
                                        <div class="border-primary card">
                                            <p class="mb-0 card-body py-2 px-3">
                                                {!! nl2br(e($requirement_item->response_answer->where('response_id', $scholar_response->id)->first()->answer)) !!}
                                            </p>
                                        </div>
                                        @break

                                    @case('radio')    
                                        @foreach ($requirement_item->options as $option)
                                            @if ( $option->responses->where('response_id', $scholar_response->id)->first() )
                                                <div class="input-group mb-1">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text border-primary bg-primary text-white">
                                                            <i class="fas fa-check-circle"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control bg-white border-primary" value="{{ $option->option }}" readonly>
                                                </div>
                                            @else
                                                <div class="input-group mb-1">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="far fa-circle"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control bg-white" value="{{ $option->option }}" readonly>
                                                </div>
                                            @endif
                                        @endforeach
                                        @break

                                    @case('check')
                                        @foreach ($requirement_item->options as $option)
                                            @if ( $option->responses->where('response_id', $scholar_response->id)->first() )
                                                <div class="input-group mb-1">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text border-primary bg-primary text-white">
                                                            <i class="fas fa-check-square"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control bg-white border-primary" value="{{ $option->option }}" readonly>
                                                </div>
                                            @else
                                                <div class="input-group mb-1">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="far fa-square"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control bg-white" value="{{ $option->option }}" readonly>
                                                </div>
                                            @endif
                                        @endforeach
                                        @break
                                @endswitch
                       
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endisset  
    </div>
@endisset
</div>
