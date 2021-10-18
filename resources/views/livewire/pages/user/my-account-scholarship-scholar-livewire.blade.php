<div class="container-fluid">
    <h5>
        <strong>
            Family Information
        </strong>
    </h5>
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
    
    <hr class="my-2">
    <div class="row mt-2">
        <div class="col-auto">
            <a href="asdsd" class="d-flex">
                <h3 class="text-primary my-0">
                    <i class="fab fa-facebook m-0"></i>
                </h3>
                <h6 class="mx-2 my-auto">
                    {{ $user->facebook->facebook_link }}
                </h6>
            </a>
        </div>
    </div>
</div>
