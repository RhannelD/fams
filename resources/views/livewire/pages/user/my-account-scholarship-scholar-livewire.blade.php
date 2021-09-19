<div class="container-fluid">
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
                            <strong>
                                {{ $scholarship_scholars->category->scholarship->scholarship }}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Category: {{ $scholarship_scholars->category->category }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Amount: Php {{ $scholarship_scholars->category->amount }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Member at: {{ date_format(new DateTime($scholarship_scholars->created_at),"M d, Y") }}
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
    </div>
</div>
