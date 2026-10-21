<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Barcode {{ $barang->kode_barcode }}</title>
    <style>
          @page { size: 60mm 35mm; margin: 0; }
 
        * { box-sizing: border-box; }
 
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eee;
        }
 
        .toolbar {
            padding: 12px;
            text-align: center;
        }
 
        .toolbar button, .toolbar a {
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #198754;
            background: #198754;
            color: #fff;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }
 
        .toolbar a { background: #fff; color: #198754; }
 
        .stiker {
            width: 60mm;
            height: 35mm;
            margin: 10px auto;
            padding: 2mm;
            background: #fff;
            border: 1px dashed #999;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
 
        .stiker .nama {
            font-size: 9pt;
            font-weight: bold;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
 
        .stiker svg { width: 54mm; height: 20mm; }
 
        @media print {
            body { background: #fff; }
            .toolbar { display: none; }
            .stiker { margin: 0; border: none; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">Cetak</button>
        <a href="#" onclick="window.close(); return false;">Batal</a>
    </div>

    <div class="stiker">
        <div class="nama">{{ $barang->nama_barang }}</div>
        <svg id="barcode"></svg>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script>
        JsBarcode('#barcode', @json($barang->kode_barcode), {
            format: 'CODE128',
            width: 2,
            height: 50,
            displayValue: true,
            fontSize: 14,
            margin: 0,
        });

        window.addEventListener('load', () => setTimeout(() => window.print(), 300));
    </script>
</body>
</html>