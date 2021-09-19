<div class="container-fluid">
    <h5>
        <strong>
            Scholarship
        </strong>
    </h5>
    <div class="row">
        @foreach ($user->scholarship_officers as $scholarship_officers)  
            <div class="col-auto">
                <table>
                    <tr>
                        <td>
                            Scholarship: 
                            <strong>
                                {{ $scholarship_officers->scholarship->scholarship }}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Position: {{ $scholarship_officers->position->position }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Member at: {{ date_format(new DateTime($scholarship_officers->created_at),"M d, Y") }}
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
    </div>
</div>
