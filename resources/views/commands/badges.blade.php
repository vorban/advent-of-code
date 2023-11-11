<div>
    <div>
        <img alt="Laravel"
             src="https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white">
        <img alt="Total Stars: {{ $years->sum() }}"
             src="https://img.shields.io/badge/total_stars%20⭐-{{ $years->sum() }}-fcd34d?style=for-the-badge">
    </div>
    <br />
    @foreach ($yearsIterator as $date)
        @if ($years->has($date->year))
            <img alt="{{ $date->year }}: {{ $years[$date->year] }}"
                 src="https://img.shields.io/badge/{{ $date->year }}%20⭐-{{ $years[$date->year] }}-{{ $years[$date->year] == 50 ? 'fcd34d' : 'f4f4f5' }}">
        @else
            <img alt="{{ $date->year }}: 0"
                 src="https://img.shields.io/badge/{{ $date->year }}%20⭐-0-a8a29e">
        @endif
        @if ($loop->iteration % 5 == 0)
            <br />
        @endif
    @endforeach
</div>
