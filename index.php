<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#01112f">
        <script data-ad-client="ca-pub-1383595818253122" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <title>Home | WebniX</title>
        <style>
            :root {
                --white-transparent: rgba(255,255,255, 0.75);
                --bg-color: #01112f;
            }
            html {
                background-color: var(--bg-color);
                background-image: url('./wall.jpg');
                background-size: cover;
                background-repeat: no-repeat;
                background-attachment: fixed;
                -webkit-user-select: none; /* Safari */
                -moz-user-select: none; /* Firefox */
                -ms-user-select: none; /* IE10+/Edge */ 
                user-select: none; /* Standard */
            }
            html, body {
                margin: 0;
                padding: 0;
                height: 100%;
                min-height: 100%;
                width: 100%;
            }
            body {
                display: flex;
                font-family: Verdana sans-serif;
                align-items: center;
                justify-content: center;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            body > div {
                box-shadow: 0 0 60px -25px var(--bg-color);
                background: var(--white-transparent);
                color: black;
                padding: 50px 0;
                text-align: center;
                min-width: 320px;
                position: relative; width: 50%;
            }
            .error {
                color: red;
            }
            .error + .temp {
                display: none;
            }.clock {
                border-bottom: 1px solid black;
                font-family: fangsong monospace;
                font-weight: 300;
                font-size: 3.5em;
                padding: 0 15px;
                opacity: .65 
            }
            .ping-results {
                display: none;
                margin: 0 auto;
                font-size: .75em;
                transition: height 1s ease-in-out .5s;
                width: 50%;
            }
            .ping-results td {
                text-indent: 10px;
            }
            .ping-results td span {
                background: palevioletred;
                border: solid 1px white;
                border-radius: 50%;
                display: inline-block;
                height: 8px;
                margin: 0 10px 0 10px;
                width: 8px;
            }
            .ping-results td span.up {
                background: yellowgreen;
            }
        </style>
    </head>
    <body>
        <div>
            <span class="clock" id="clock"></span>
            <h2>Pi diagnostics</h2>
            <p>CPU Temperature: 
                &nbsp;<span id="temp">...</span>
                <span class="temp">&#176;<span>
            </p>
            <table class="ping-results" id="pingParent">
                <thead>
                    <tr>
                        <th>Hosts</th>
                    </tr>
                </thead>
                <tbody id="ping"></tbody>
            </table>
        </div>
        <script>
            const pingIntervalDelay = <?=$_GET['ping'] ?? 30000 ?>;
            const tempIntervalDelay = <?=$_GET['temp'] ?? 5000 ?>;

            async function getTemperature() {
                const response = await fetch('temp.php',{ method: 'GET', cache: 'no-cache'});
                return response.json();
            };

            async function getPingResults() {
                const response = await fetch('ping.php', { method: 'GET', cache: 'no-cache'});
                return response.json();
            };

            let zeroPrefix = function (digit) {
                return parseInt(digit) <=9 ? '0' + digit : digit;
            };

            
            // Temp function.
            const tempFunc = function () {
                let r = getTemperature(); 
                r.then(function (data) {
                    temperatureElem.innerHTML = data.temp.toFixed(1).replace('.',',');
                }).catch(function (err) {
                    temperatureElem.innerHTML = 'ERR';
                    temperatureElem.classList.add('error');
                    clearInterval(temperatureInterval);
                });
            };

            // Ping function.
            const pingFunc = function () {
                let r = getPingResults();
                let numRows = 0;

                let newPingElem = document.createElement('tbody');
                newPingElem.id = 'ping';

                r.then(function (data) {
                    data.forEach(row => {
                        let tr = document.createElement('tr');

                        td = document.createElement('td');
                        td.title = row.ip;
                        td.innerHTML = row.name;

                        span = document.createElement('span');
                        span.classList.add(row.status.toLowerCase());
                        span.title = row.status.toLowerCase();

                        td.appendChild(span);
                        tr.appendChild(td);

                        newPingElem.appendChild(tr);

                        numRows++;
                    });
                    
                    if (numRows) {
                        pingParent.style.display = 'initial';
                        pingElem.parentNode.replaceChild(newPingElem, pingElem);
                        pingElem = document.querySelector("#ping");
                    } else {
                        pingElem.innerHTML = "";
                        pingParent.style.display = 'none';
                    }
                }).catch(function (err) {
                    pingElem.innerHTML = 'ERR';
                    pingElem.classList.add('error');
                    clearInterval(pingInterval);
                });
            };

            // Show temperature.
            let temperatureElem = document.querySelector('#temp');
            let temperatureInterval = setInterval(function() {
                tempFunc();
            }, tempIntervalDelay);

            // Show ping results.
            let pintParent = document.querySelector('#pingParent');
            let pingElem = document.querySelector("#ping");
            let pingInterval = setInterval(function () {
                pingFunc();
            }, pingIntervalDelay);

            let clockElem = document.querySelector('#clock');
            let clock = setInterval(function () {
                var time = new Date();
                clockElem.innerHTML = '' + zeroPrefix(time.getHours()) + ':' + zeroPrefix(time.getMinutes()) + ':' + zeroPrefix(time.getSeconds());
            });

            // First calls.
            tempFunc();
            pingFunc();
        </script>
    </body>
</html>