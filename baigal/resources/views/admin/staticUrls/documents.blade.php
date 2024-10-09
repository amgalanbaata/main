<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            font-size: 30px;
            font-weight: 400;
            margin-bottom: 40px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: green;
        }
        p {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        button {
            padding: 10px 8px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
    <body>
        <div class="container">
                <h1>Холбогдох баримт бичгүүд</h1>
                <p>Хөрсийг бохирдол, доройтлоос хамгаалах чиглэлийн бодлого, үйл ажиллагаа тусгагдсан хууль, эрх зүйн болон бодлогын баримт бичиг:</p>
                {{-- <p>Энэхүү стандарт нь Монгол улсын хөрсний орон зайн өгөгдлийг бүрдүүлэх үйл ажиллагаанд хамаарна.</p> --}}
                <button onclick="window.location.href='pdfurl?type=documents';">Дэлгэрэнгүй</button>
        </div>
</body>
</html>
