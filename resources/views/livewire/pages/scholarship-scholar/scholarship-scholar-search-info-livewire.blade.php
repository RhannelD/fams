<tr>
    <td colspan="6" class="p-0 border-0">
        <div wire:ignore.self id="collapse{{ $scholar->user_id }}" data-parent="#accordion" class="collapse acc p-0" >
            <div class="card mb-3 shadow-sm">
                <div class="card-body p-2">
                    <div class="row">
                        <div class="d-flex col-md-6">
                            <div class="col-auto pb-0">
                                <table class="table table-borderless table-sm m-0">
                                    <thead>
                                        <tr>
                                            <th colspan="2">
                                                Scholar Info
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>ID:</td>
                                            <td>
                                                {{ $scholar->user_id }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Full Name:</td>
                                            <td>
                                                {{ $scholar->user->fmlname() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phonenumber:</td>
                                            <td>
                                                {{ $scholar->user->phone }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Email:</td>
                                            <td>
                                                {{ $scholar->user->email }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sex:</td>
                                            <td>
                                                {{ $scholar->user->gender }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Address:</td>
                                            <td>{{ $scholar->user->address() }}</td>
                                        </tr>
                                        <tr>
                                            <td>Religion:</td>
                                            <td>
                                                {{ $scholar->user->religion }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Birth Date:</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($scholar->user->birthday)->format("M d, Y") }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Birth Place:</td>
                                            <td>
                                                {{ $scholar->user->birthplace }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex col-md-6">
                            <div class="col-auto pb-0">
                                <table class="table table-borderless table-sm m-0">
                                    <thead>
                                        <tr>
                                            <th colspan="2">
                                                Family Information
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Mother's Name:</td>
                                            <td>{{ $scholar->user->scholar_info->mother_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Mother's Occupation:</td>
                                            <td>{{ $scholar->user->scholar_info->mother_occupation }}</td>
                                        </tr>
                                        <tr>
                                            <td>Mother's Birth Date:</td>
                                            <td>{{ \Carbon\Carbon::parse($scholar->user->scholar_info->mother_birthday)->format("M d, Y") }}</td>
                                        </tr>
                                        <tr>
                                            <td>Mother's Educational Attainment:</td>
                                            <td>{{ $scholar->user->scholar_info->mother_educational_attainment }}</td>
                                        </tr>
                                        <tr>
                                            <td>Living:</td>
                                            <td>{{ $scholar->user->scholar_info->mother_living? 'Yes': 'No' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Father's Name:</td>
                                            <td>{{ $scholar->user->scholar_info->father_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Father's Occupation:</td>
                                            <td>{{ $scholar->user->scholar_info->father_occupation }}</td>
                                        </tr>
                                        <tr>
                                            <td>Father's Birth Date:</td>
                                            <td>{{ \Carbon\Carbon::parse($scholar->user->scholar_info->father_birthday)->format("M d, Y") }}</td>
                                        </tr>
                                        <tr>
                                            <td>Father's Educational Attainment:</td>
                                            <td>{{ $scholar->user->scholar_info->father_educational_attainment }}</td>
                                        </tr>
                                        <tr>
                                            <td>Living:</td>
                                            <td>{{ $scholar->user->scholar_info->father_living? 'Yes': 'No' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex col-md-6">
                            <div class="col-auto pb-0">
                                <table class="table table-borderless table-sm m-0">
                                    <thead>
                                        <tr>
                                            <th colspan="2">
                                                Educational Information
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>SR-Code:</td>
                                            <td>{{ $scholar->user->scholar_info->srcode }}</td>
                                        </tr>
                                        <tr>
                                            <td>Course:</td>
                                            <td>{{ $scholar->user->scholar_info->course->course }}</td>
                                        </tr>
                                        <tr>
                                            <td>Year:</td>
                                            <td>{{ $scholar->user->scholar_info->year }}</td>
                                        </tr>
                                        <tr>
                                            <td>Semester</td>
                                            <td>{{ $scholar->user->scholar_info->semester }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex col-md-6">
                            <div class="col-auto pb-0">
                                <table class="table table-borderless table-sm m-0">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">
                                                Scholarship
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Category:</td>
                                            <td>
                                                {{ $scholar->category->category }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Amount:</td>
                                            <td>
                                                {{ $scholar->category->amount }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Year & Sem:</td>
                                            <td>
                                                {{ $scholar->acad_year }}-{{ $scholar->acad_year+1 }} 
                                                {{ $scholar->acad_sem=='1'? 'First': 'Second' }} Sem
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Date Joined:</td>
                                            <td>
                                                {{ date_format(new DateTime($scholar->created_at),"M d, Y") }}
                                            </td>
                                        </tr>
                                        @if ( $scholar->user->scholarship_scholars->count() > 1 )
                                            <tr>
                                                <th colspan="2">
                                                    Other Scholarships: 
                                                    <span class="badge badge-primary pt-1 my-auto">
                                                        {{ $scholar->user->scholarship_scholars->count()-1 }}
                                                    </span>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <ul class="my-auto">
                                                        @foreach ($scholar->user->scholarship_scholars->where('category_id', '!=', $scholar->category_id) as $scholarship_scholar)
                                                            <li>
                                                                {{ $scholarship_scholar->category->scholarship->scholarship }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
