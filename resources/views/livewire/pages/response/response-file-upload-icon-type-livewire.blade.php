<div>
    @isset( $file_extension )
        @switch( $file_extension )
            @case( 'pdf' )
                <i class="fas fa-file-pdf"></i>
                @break
            @case( 'docx' )
                <i class="fas fa-file-word"></i>
                @break
            @case( 'xlsx' )
            @case( 'xlsm' )
            @case( 'xlsb' )
            @case( 'xlsb' )
                <i class="fas fa-file-excel"></i>
                @break
            @case( 'csv' )
                <i class="fas fa-file-csv"></i>
                @break
            @case( 'txt' )
                <i class="fas fa-file-alt"></i>
                @break
            @case( 'png' )
            @case( 'gif' )
            @case( 'jpeg' )
            @case( 'jpg' )
            @case( 'svg' )
            @case( 'webp' )
                <i class="fas fa-file-image"></i>
                @break
            @case( 'html' )
                <i class="fas fa-file-code"></i>
                @break
            @default
                <i class="fas fa-file"></i>
        @endswitch
    @endisset
</div>
