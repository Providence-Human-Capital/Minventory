<div>
    <h3>Clinic Data</h3>
    <ul>
        @foreach ($data as $item)
            <li>{{ $item->your_column_name }}</li> <!-- Adjust according to your model -->
        @endforeach
        {{dd($chartData)}}
    </ul>
</div>