<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Хөрсний шинжилгээ</title>
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
            /* line-height: .75em; */
            font-weight: 400;
            margin-bottom: 40px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* color: #4DA20F; */
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
                <h1>Хөрсний лаборатори, шинжилгээний газар</h1>
                <p>Хөрсний шинжилгээг албан ёсны байгууллагаар дамжуулан хийдэг ба бүх төрлийн үзүүлэлтээр гаргадаг байгаа.</p>
                <p>Энэхүү шинжилгээг хийлгэснээр хөрсний бохирдлын хэм хэмжээ болон цаашдын үйл ажиллагаанд хэрэг болох болно. Дараах жагсаалтаар шинжилгээ хийх лабораторийн мэдээллийг авч болно.</p>
                <button onclick="window.location.href='pdfurl?type=laboratoryList';">Дэлгэрэнгүй</button>
        </div>
</body>
</html>
