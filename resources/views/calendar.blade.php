<style>
    :root {
        --bg-color: #000;
        --dot-past: #fff;
        --dot-future: #222;
        --dot-today: #2563eb;
        --text-muted: #777;

        font-size: 21px;
    }

    body {
        background-color: var(--bg-color);
        height: 100vh;
        margin: 0;
        font-family: 'San Francisco', sans-serif;
        position: relative;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 3rem 4rem;
        position: fixed;
        top: 54%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .month-name {
        color: var(--text-muted);
        font-size: 1.5rem;
        display: block;
        margin-bottom: 1rem;
    }

    .dots-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.95rem;
        justify-items: center;
    }

    .dot {
        width: 0.85rem;
        height: 0.85rem;
        border-radius: 50%;
        background-color: var(--dot-future);

        &.empty {
            background-color: transparent;
        }

        &.past {
            background-color: var(--dot-past);
        }

        &.today {
            background-color: var(--dot-today);
        }
    }
</style>

<div class="calendar-grid">
    @foreach($calendar as $month)
        <div class="month">
            <span class="month-name">{{ $month['name'] }}</span>
            <div class="dots-grid">
                @for ($i = 0; $i < $month['blank_days']; $i++)
                    <div class="dot empty"></div>
                @endfor

                @foreach($month['days'] as $day)
                    <div class="dot {{ $day['is_today'] ? 'today' : ($day['is_past'] ? 'past' : '') }}"></div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
