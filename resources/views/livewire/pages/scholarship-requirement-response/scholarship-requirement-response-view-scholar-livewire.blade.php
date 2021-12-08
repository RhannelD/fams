<div>
    <div>
        <table>
            <thead>
                <tr>
                    <th colspan="2">
                        Scholar Info
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Full Name:</td>
                    <td>
                        {{ $scholar_response->user->fmlname() }}
                    </td>
                </tr>
                <tr>
                    <td>Phonenumber:</td>
                    <td>
                        {{ $scholar_response->user->phone }}
                    </td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>
                        {{ $scholar_response->user->email }}
                    </td>
                </tr>
                <tr>
                    <td>Sex:</td>
                    <td>
                        {{ $scholar_response->user->gender }}
                    </td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td>{{ $scholar_response->user->address() }}</td>
                </tr>
                <tr>
                    <td>Religion:</td>
                    <td>
                        {{ $scholar_response->user->religion }}
                    </td>
                </tr>
                <tr>
                    <td>Birth Date:</td>
                    <td>
                        {{ \Carbon\Carbon::parse($scholar_response->user->birthday)->format("M d, Y") }}
                    </td>
                </tr>
                <tr>
                    <td>Birth Place:</td>
                    <td>
                        {{ $scholar_response->user->birthplace }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr class="my-2">
    <div>
        <table>
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
                    <td>{{ $scholar_response->user->scholar_info->srcode }}</td>
                </tr>
                <tr>
                    <td>Course:</td>
                    <td>{{ $scholar_response->user->scholar_info->course->course }}</td>
                </tr>
                <tr>
                    <td>Year:</td>
                    <td>{{ $scholar_response->user->scholar_info->year }}</td>
                </tr>
                <tr>
                    <td>Semester</td>
                    <td>{{ $scholar_response->user->scholar_info->semester }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr class="my-2">
    <div>
        <table>
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
                    <td>{{ $scholar_response->user->scholar_info->mother_name }}</td>
                </tr>
                <tr>
                    <td>Mother's Occupation:</td>
                    <td>{{ $scholar_response->user->scholar_info->mother_occupation }}</td>
                </tr>
                <tr>
                    <td>Mother's Birth Date:</td>
                    <td>{{ \Carbon\Carbon::parse($scholar_response->user->scholar_info->mother_birthday)->format("M d, Y") }}</td>
                </tr>
                <tr>
                    <td>Mother's Educational Attainment:</td>
                    <td>{{ $scholar_response->user->scholar_info->mother_educational_attainment }}</td>
                </tr>
                <tr>
                    <td>Living:</td>
                    <td>{{ $scholar_response->user->scholar_info->mother_living? 'Yes': 'No' }}</td>
                </tr>
                <tr>
                    <td>Father's Name:</td>
                    <td>{{ $scholar_response->user->scholar_info->father_name }}</td>
                </tr>
                <tr>
                    <td>Father's Occupation:</td>
                    <td>{{ $scholar_response->user->scholar_info->father_occupation }}</td>
                </tr>
                <tr>
                    <td>Father's Birth Date:</td>
                    <td>{{ \Carbon\Carbon::parse($scholar_response->user->scholar_info->father_birthday)->format("M d, Y") }}</td>
                </tr>
                <tr>
                    <td>Father's Educational Attainment:</td>
                    <td>{{ $scholar_response->user->scholar_info->father_educational_attainment }}</td>
                </tr>
                <tr>
                    <td>Living:</td>
                    <td>{{ $scholar_response->user->scholar_info->father_living? 'Yes': 'No' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr class="my-2">
    <div>
        <table>
            <tbody>
                <tr>
                    <th colspan="2">
                        Scholarships
                    </th>
                </tr>
                <tr>
                    <td>
                        <ul class="my-auto">
                            @forelse ($scholar_response->user->scholarship_scholars as $scholarship_scholar)
                                <li>
                                    {{ $scholarship_scholar->category->scholarship->scholarship }}
                                </li>
                            @empty
                                None...
                            @endforelse
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>