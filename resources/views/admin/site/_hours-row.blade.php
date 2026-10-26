<div class="repeat-row hours-row">
    <div class="form-row">
        <label for="hours-{{ $i }}-from">From</label>
        <select id="hours-{{ $i }}-from" name="hours[{{ $i }}][from]">
            @foreach($days as $code => $day)
                <option value="{{ $code }}" {{ ($row['from'] ?? '') === $code ? 'selected' : '' }}>{{ $day['long'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-row">
        <label for="hours-{{ $i }}-to">To</label>
        <select id="hours-{{ $i }}-to" name="hours[{{ $i }}][to]">
            @foreach($days as $code => $day)
                <option value="{{ $code }}" {{ ($row['to'] ?? '') === $code ? 'selected' : '' }}>{{ $day['long'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-row">
        <label for="hours-{{ $i }}-opens">Opens</label>
        <input type="time" id="hours-{{ $i }}-opens" name="hours[{{ $i }}][opens]" value="{{ $row['opens'] ?? '' }}">
    </div>
    <div class="form-row">
        <label for="hours-{{ $i }}-closes">Closes</label>
        <input type="time" id="hours-{{ $i }}-closes" name="hours[{{ $i }}][closes]" value="{{ $row['closes'] ?? '' }}">
    </div>
    <button type="button" class="btn-row-remove" data-remove-row aria-label="Remove this timing">Remove</button>
</div>
