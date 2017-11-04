<?php
    $convertNow             = isset($_POST['convert_result']);
    $displayConversionForm  = $convertNow ? "style = 'display: none'" : "";
?>

<!DOCTYPE html>
    <html>
    <head><meta charset="utf-8"></head>

    <body>
        <form method = "POST" action = "" <?php echo $displayConversionForm ?> >
            <p><input id = "button-submit" type = "submit" value = "Conversie in phpBB"></p>
            <p>
                <label>Titlu tabel:</label>
                <input type="text"
                       placeholder = "titlu tabel"
                       name = "titlu_tabel">
            </p>
            <p>
                <label>Rânduri x coloane:</label>
                <input type = "number"
                       name = "rows"
                       id = "rows"
                       value = "1"
                       min = "1" max = "30"
                       onchange="creazaTabelOnScreen()">
                <input type = "number"
                       name = "cols"
                       value = "1"
                       id = "cols"
                       min = "1" max = "30"
                       onchange="creazaTabelOnScreen()">
            </p>
            <label><input type="checkbox" name="center" value="true" checked>Centrează conţinut</label>
            <p id = "tabelOnScreen"><label>Completează aici conţinutul:<br></label></p>
            <input type = "hidden" name = "convert_result">
            <p><input id = "button-submit" type = "submit" value = "Conversie in phpBB"></p>
        </form>
        <?php if ($convertNow) echo getTableConversion() ?>
    </body>

    <script>

        window.onload = creazaTabelOnScreen();

        function creazaTabelOnScreen()
        {
            var rowsContainer = document.getElementById('tabelOnScreen');
            var rows   = document.getElementById("rows").value;
            var cols   = document.getElementById("cols").value;

            // TODO remove randuri coloane
//            if (cols < boxContainer.childElementCount-1) {
//                while (boxContainer.childElementCount-1 > currNoPart) {
//                    boxContainer.removeChild(boxContainer.lastChild);
//                }
//
//            // add boxes
//            }

            // adauga randuri noi
            while (rows > rowsContainer.childElementCount-1) {
                var currentRowIndex = rowsContainer.childElementCount-1;
                var row = document.createElement("p");
                row.id = "row" + (currentRowIndex);
                document.getElementById("tabelOnScreen").appendChild(row);
            }

            // adauga coloane noi
            for (i = 0; i <= rows; i++) {
                var parentRowId = "row" + i;
                var currentRow = document.getElementById(parentRowId);
                while (cols > currentRow.childElementCount) {
                    var currentColIndex = currentRow.childElementCount;
                    var celula = document.createElement("textarea");
                    celula.name = "r" + i + "c" + currentColIndex;
                    celula.placeholder = "R " + i + " - C " + currentColIndex;
                    currentRow.appendChild(celula);
                }
            }
        }
    </script>
</html>

<?php
function getTableConversion()
{
    $rows   = $_POST['rows'];
    $cols   = $_POST['cols'];
    $titlu  = isset($_POST['titlu_tabel']) ? $_POST['titlu_tabel'] : "";
    $center = isset($_POST['center']);

    if ($titlu) {
        $titlu = "<h2>[size=150][b]".$titlu."[/b][/size]</h2><br>";
    }

    $result = "<pre style = \"white-space: pre-wrap; background-color: lightgrey\">";
    $tabel = "[table]";

    for ($r = 0; $r < $rows; $r++) {
        // adaugare randuri
        $randulCurent = "[row]";

        // adugare coloane
        for ($c = 0; $c < $cols; $c++) {
            // open tags
            $celula = "[cell]";
            $celula .= $center ? "[center]" : "";

            // content
            $contentId = "r$r"."c$c";
            $celula .= isset($_POST[$contentId]) ? $_POST[$contentId] : "";

            // close tags
            $celula .= $center ? "[/center]" : "";
            $celula .= "[/cell]";

            $randulCurent .= $celula;
        }

        $randulCurent .= "[/row]";
        $tabel .= $randulCurent;
    }
    $tabel .= "[/table]";

    $result .= $titlu . $tabel . "</pre>";

    return $result;
}