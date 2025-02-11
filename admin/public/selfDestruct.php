<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tự hủy</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
<style>
            .flex{
                top: 0;
                left: 0;
                background-color: white;
                z-index: 99999;
                position: fixed;
                height: 100vh;
                width: 100vw;
                text-align: center;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            button{
                

                margin-top:10vh;
                height: 80vh;
                width: 80vw;
                background-color:#c92127;
                font-size:30vh;
                border-radius:5vh;
                cursor:pointer;
            }   
        </style>
        <div class="flex">
        <button onclick="selfDestruct();">Tự hủy</button>
        </div>

    <script>
                function selfDestruct() {
            $.ajax({
                url: "../controller/admin/selfDestruct.controller.php",
                type: "post",
                dataType: "html",
            }).done(function(result) {
                location.reload();
            })
        }
    </script>
</body>

</html>