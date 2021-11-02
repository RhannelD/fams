<div class="container-fluid px-md-3 px-0">
    <div class="d-flex">
        <div class="ml-0 mr-auto overflow-hidden">
            <a class="d-flex" 
                @isset($user->facebook)
                    href="{{ $user->facebook->facebook_link }}" target="blank" 
                @endisset
                >
                <h3 class="text-primary my-0">
                    <i class="fab fa-facebook m-0"></i>
                </h3>
                <h6 class="mx-2 my-auto {{ isset($user->facebook)? '': 'text-dark' }}">
                    {{ isset($user->facebook)? $user->facebook->facebook_link: 'No Linked Facebook account.' }}
                </h6>
            </a>
        </div>
        <button class="mr-0 btn btn-sm btn-primary" data-toggle="modal" data-target="#update-facebook-modal"
            wire:click="$emitTo('user.change-facebook-livewire', 'reset_values')"
            >
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <hr class="my-2">

    <div class="d-flex">
        <h4 class="my-auto ml-0 mr-auto">
            <strong>
                Education Information
            </strong>
        </h4>
        <div class="mr-0">
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#update-education-info-modal"
                wire:click="$emitTo('user.update-education-information-livewire', 'reset_values')"
                >
                <i class="fas fa-edit"></i>
            </button>
        </div>
    </div>
    <table>
        <tbody>
            <tr>
                <td>Course:</td>
                <td>{{ $user->scholar_info->course->course }}</td>
            </tr>
            <tr>
                <td>Year:</td>
                <td>{{ $user->scholar_info->year }}</td>
            </tr>
            <tr>
                <td>Semester:</td>
                <td>{{ $user->scholar_info->semester }}</td>
            </tr>
        </tbody>
    </table>
    <hr class="my-2">

    <div class="d-flex">
        <h4 class="my-auto ml-0 mr-auto">
            <strong>
                Family Information
            </strong>
        </h4>
        <div class="mr-0">
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#update-family-info-modal"
                wire:click="$emitTo('user.update-family-information-livewire', 'reset_values')"
                >
                <i class="fas fa-edit"></i>
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-auto">
            <table>
                <tbody>
                    <tr>
                        <th colspan="2">
                            Mother's Information
                        </th>
                    </tr>
                    <tr>
                        <td>Mother's Name:</td>
                        <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->mother_name }}</td>
                    </tr>
                    <tr>
                        <td>Mother's Occupation:</td>
                        <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->mother_occupation }}</td>
                    </tr>
                    <tr>
                        <td>Mother's Birth Date:</td>
                        <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->mother_birthday }}</td>
                    </tr>
                    <tr>
                        <td>Mother's Educational Attainment:</td>
                        <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->mother_educational_attainment }}</td>
                    </tr>
                    <tr>
                        <td>Living:</td>
                        <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->mother_living? 'Yes': 'No' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-auto">
            <table>
                <tbody>
                    <tr>
                        <th colspan="2">
                            Father's Information
                        </th>
                    </tr>
                    <tr>
                        <td>Father's Name:</td>
                        <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->father_name }}</td>
                    </tr>
                    <tr>
                        <td>Father's Occupation:</td>
                        <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->father_occupation }}</td>
                    </tr>
                    <tr>
                        <td>Father's Birth Date:</td>
                        <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->father_birthday }}</td>
                    </tr>
                    <tr>
                        <td>Father's Educational Attainment:</td>
                        <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->father_educational_attainment }}</td>
                    </tr>
                    <tr>
                        <td>Living:</td>
                        <td class="pl-sm-1 pl-md-2">{{ $user->scholar_info->father_living? 'Yes': 'No' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @if ( $user->scholarship_scholars->count()>0 )  
        <hr>
        <h5>
            <strong>
                Scholarship Acquired
            </strong>
        </h5>
        <div class="row">
            @foreach ($user->scholarship_scholars as $scholarship_scholars)  
                <div class="col-auto">
                    <table>
                        <tr>
                            <td>
                                Scholarship: 
                            </td>
                            <td>
                                <strong>
                                    {{ $scholarship_scholars->category->scholarship->scholarship }}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Category: 
                            </td>
                            <td>
                                {{ $scholarship_scholars->category->category }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Amount: 
                            </td>
                            <td>
                                Php {{ $scholarship_scholars->category->amount }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Member at:
                            </td>
                            <td>
                                {{ date_format(new DateTime($scholarship_scholars->created_at),"M d, Y") }}
                            </td>
                        </tr>
                    </table>
                </div>
            @endforeach
        </div>
    @endif
</div>
