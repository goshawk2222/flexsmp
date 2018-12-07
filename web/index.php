<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="Generator" content="EditPlus®">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <title>Document</title>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
    <!-- <script src="js/demo.js"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dynatable/0.3.1/jquery.dynatable.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script type="text/javascript" src="js/jquery.bracket.min.js"></script>
    <link rel="stylesheet" type="text/css" href="js/jquery.bracket.min.css" />
    <script type="text/javascript" src="lib/Bacon-1ab32ffb.min.js"></script>
    <script type="text/javascript" src="lib/lodash-2.2.1.min.js"></script>
    <script type="text/javascript" src="lib/live.js"></script>
    <script type="text/javascript" src="lib/handlebars.1.0.0.js"></script>
    <script type="text/javascript" src="lib/jquery.group.min.js"></script>
    <link type="text/css" rel="stylesheet" href="lib/jquery.group.min.css" />
    <style>
        th {
            background: rgba(60, 27, 252, 0.822);
        }
        
        thead th {
            background-color: rgba(60, 27, 252, 0.822);
            color: white;
        }
        
        tbody tr td {
            width: 300px;
        }
    </style>

</head>

<body>

    <div class="results">
        <textarea id="resizable" rows="10" cols="50"></textarea>
    </div>
    <!-- League -->
    <div>
        <button id="league" class="ui-button ui-widget ui-corner-all">Generate League</button>
    </div>
    <div id="leaguetable" style="width:100%">

    </div>
    <!-- League -->
    <div>
        <button id="tournament" class="ui-button ui-widget ui-corner-all">Generate Tournament</button>
    </div>
    <div id="show-tournament">
        <h3>knockout</h3>
        <div class="demo"></div>
    </div>
    <div>
        <button id="save" class="ui-button ui-widget ui-corner-all">SAVE</button>
    </div>

    <div id="top">
        <h3>Top Player</h3>
        <div id="result">
        </div>
    </div>
</body>

</html>
<script>
    $(function() {
        function showResult() {
            if (typeof(Storage) !== "undefined") {
                if (localStorage.top) {
                    var recode = JSON.parse(localStorage.top);

                    var tbodyStr = "";
                    for (var i = recode.length - 1; i >= 0; i--) {
                        tbodyStr = "<tr><td>" + recode[i].name + "</td><td>" + recode[i].score + "</td></tr>" + tbodyStr;
                    }
                    var tableStr = "<table><thead><th>Trainer</th><th>Points</th></thead><tbody>" + tbodyStr + "</tbody></table>";
                    $('div#top > div#result').html(tableStr);
                }
            } else {
                // document.getElementById("result").innerHTML = "Sorry, your browser does not support web storage...";
            }
        }
        $("button#league").click(function(event) {
            var div = document.getElementById('leaguetable');
            while (div.firstChild) {
                div.removeChild(div.firstChild);
            }
            var tmparrayName = $('#resizable').val().split('\n');
            tmparrayName = removeDups(tmparrayName);
            var arrayName = tmparrayName.filter(function(el) {
                if (el != null && el != "")
                    return el;
            });
            var tmpNameArray = [];
            var idarray = [];
            var loadhtml;
            var i, j, temparray, chunk = 3;
            loadhtml = "<table>";
            for (i = 0, j = arrayName.length; i < j; i += chunk) {
                loadhtml += "<td><div id='" + (i + 1) + "'></div></td>";
                temparray = arrayName.slice(i, i + chunk);
                tmpNameArray.push(temparray);
                idarray.push(i + 1);
            }
            loadhtml += "</table>";
            document.getElementById("leaguetable").innerHTML = loadhtml;
            for (var idx = 0; idx < idarray.length; idx++) {
                var id = "div#" + idarray[idx];
                var groupData = {};
                groupData["teams"] = [];
                groupData["matches"] = [{
                    "id": 1,
                    "round": 0,
                    "a": {
                        "team": 0,
                        "score": 0
                    },
                    "b": {
                        "team": 0,
                        "score": 0
                    }
                }, {
                    "id": 2,
                    "round": 0,
                    "a": {
                        "team": 0,
                        "score": 0
                    },
                    "b": {
                        "team": 0,
                        "score": 0
                    }
                }, {
                    "id": 3,
                    "round": 0,
                    "a": {
                        "team": 0,
                        "score": 0
                    },
                    "b": {
                        "team": 0,
                        "score": 0
                    }
                }];
                for (var tn = 0; tn < tmpNameArray[idx].length; tn++) {
                    var tndata = {};
                    tndata["id"] = tn + 1;
                    tndata["name"] = tmpNameArray[idx][tn];
                    groupData["teams"].push(tndata);
                }
                $(id).group({
                    init: groupData,
                    save: function(state) {
                        // Write your storage code here, now just display JSON above
                        $('pre').text(JSON.stringify(state, undefined, 2))
                    }
                });
            }

        });
        // SAVE ARRAY
        $('button#save').click(function(e) {
            function compare(a, b) {
                if (a.score < b.score)
                    return 1;
                if (a.score > b.score)
                    return -1;
                return 0;
            }

            function saveTop(top) {
                if (typeof(Storage) !== "undefined") {
                    if (localStorage.top) {
                        var recode = JSON.parse(localStorage.top);
                        var i_top = recode.findIndex(x => x.name == top);

                        if (i_top != -1) {
                            recode[i_top].score++;
                        } else {
                            recode.push({
                                'name': top,
                                'score': 1
                            });
                        }
                        recode.sort(compare);

                        localStorage.top = JSON.stringify(recode);
                    } else {
                        localStorage.top = JSON.stringify([{
                            'name': top,
                            'score': 1
                        }]);
                    }
                    showResult();
                } else {
                    // document.getElementById("result").innerHTML = "Sorry, your browser does not support web storage...";
                }
            }

            var win = $('#show-tournament div.team.win.highlightWinner>div.label');
            if (win.length > 0) saveTop(win[0].textContent);
            e.preventDefault();
        });
        // click Tournament
        $("button#tournament").click(function(event) {
            var W = $('div.standings td:nth-child(2)').text();
            var inputElements = $('div.standings td:nth-child(1) > input.name');
            var wins = [];
            for (var i = 0; i < W.length; i++) {
                if (parseInt(W[i]) > 0) {
                    wins.push($(inputElements[i]).attr('value'));
                }
            }

            function RandomShuffle(o) {
                var newArr = [];
                for (var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);

                var len = Math.pow(2, Math.ceil(Math.log(o.length) / Math.log(2)) - 1),
                    _i;
                var len_team = 2 * len;
                for (var _j = len - 1; _j >= 0; _j--) {
                    newArr.push([]);
                }
                for (_i = 0; _i < o.length; _i++) {
                    newArr[_i % len].push(o[_i]);
                }
                _i--;
                while (++_i < len_team) {
                    newArr[_i % len].push(null);
                }
                return newArr;
            };

            var saveData = {
                teams: RandomShuffle(wins),
                results: []
            };

            /* Called whenever bracket is modified
             *
             * data:     changed bracket object in format given to init
             * userData: optional data given when bracket is created.
             */
            function saveFn(data, userData) {
                // var json = jQuery.toJSON(data)
                // $('#saveOutput').text('POST '+userData+' '+json)
                /* You probably want to do something like this
                jQuery.ajax("rest/"+userData, {contentType: 'application/json',
                                                dataType: 'json',
                                                type: 'post',
                                                data: json})
                */
            }

            $(function() {
                var container = $('div#show-tournament .demo')
                container.bracket({
                    init: saveData,
                    save: function() {},
                    disableToolbar: true,
                    roundMargin: 150,
                    userData: "http://myapi"
                })

                /* You can also inquiry the current data */
                // var data = container.bracket('data')
                // $('#dataOutput').text(jQuery.toJSON(data))
            })
        });
        localStorage.removeItem("top");
        showResult();
    });

    function removeDups(names) {
        let unique = {};
        names.forEach(function(i) {
            if (!unique[i]) {
                unique[i] = true;
            }
        });
        return Object.keys(unique);
    }
</script>