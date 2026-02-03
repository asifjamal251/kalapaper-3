{{ html()->form('PUT', route('admin.' . request()->segment(2) . '.update', $reel->id))->attribute('enctype', 'multipart/form-data')->id('splitForm')->open() }}



{{-- Jumbo Info --}}
<div class="card mb-3">
    <div class="card-body">
        <table class="table table-bordered border-dark table-sm mb-0">
            <tr>
                <th>Quality</th>
                <th>GSM</th>
                <th>Width</th>
                <th>Weight</th>
                <th>Handling Unit</th>
            </tr>
            <tr>
                <td>{{ $reel->quality->name_with_code }}</td>
                <td>{{ $reel->gsm }}</td>
                <td>
                    {{ $reel->width }}
                    <input type="hidden" id="total_width" value="{{ $reel->width }}">
                </td>
                <td>
                    {{ $reel->weight }}
                    <input type="hidden" id="total_weight" value="{{ $reel->weight }}">
                </td>
                <td>{{ $reel->handling_unit }}</td>
            </tr>
        </table>
    </div>
</div>

{{-- Split Form --}}
<div class="card">
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-3">
                <label>Side A Width</label>
                <input type="number" step="0.01" name="width_a" id="width_a"
                       class="form-control" required>
            </div>

            <div class="col-md-3">
                <label>Side A Weight</label>
                <input type="number" step="0.001" id="weight_a"
                       class="form-control" readonly>
            </div>

            <div class="col-md-3">
                <label>Side B Width</label>
                <input type="number" step="0.01" id="width_b"
                       class="form-control" readonly>
            </div>

            <div class="col-md-3">
                <label>Side B Weight</label>
                <input type="number" step="0.001" id="weight_b"
                       class="form-control" readonly>
            </div>

        </div>

        {{-- Hidden fields sent to backend --}}
        <input type="hidden" name="splits[0][width]" id="split_width_a">
        <input type="hidden" name="splits[0][weight]" id="split_weight_a">

        <input type="hidden" name="splits[1][width]" id="split_width_b">
        <input type="hidden" name="splits[1][weight]" id="split_weight_b">
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-success">
        Split Jumbo Reel
    </button>
</div>

{{ html()->form()->close() }}
<script>
document.getElementById('width_a').addEventListener('input', function () {

    let totalWidth  = parseFloat(document.getElementById('total_width').value);
    let totalWeight = parseFloat(document.getElementById('total_weight').value);
    let widthA      = parseFloat(this.value);

    if (!widthA || widthA <= 0 || widthA >= totalWidth) {
        document.getElementById('width_b').value = '';
        document.getElementById('weight_a').value = '';
        document.getElementById('weight_b').value = '';
        return;
    }

    let widthB = totalWidth - widthA;

    let weightA = (widthA / totalWidth) * totalWeight;
    let weightB = totalWeight - weightA;

    // UI
    document.getElementById('width_b').value  = widthB.toFixed(2);
    document.getElementById('weight_a').value = weightA.toFixed(2);
    document.getElementById('weight_b').value = weightB.toFixed(2);

    // Hidden submit values
    document.getElementById('split_width_a').value  = widthA.toFixed(2);
    document.getElementById('split_weight_a').value = weightA.toFixed(2);

    document.getElementById('split_width_b').value  = widthB.toFixed(2);
    document.getElementById('split_weight_b').value = weightB.toFixed(2);
});
</script>