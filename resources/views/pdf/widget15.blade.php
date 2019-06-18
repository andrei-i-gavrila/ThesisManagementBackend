<span class="grade1-5">
    @for($i = 1; $i <= 5; $i++){{ $i }}<span style="text-decoration: underline">{!!  $i == $checked ? '&nbsp;X&nbsp;' : '&nbsp;&nbsp;&nbsp;' !!}</span> @endfor
</span>
