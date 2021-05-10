const request = require('request');
const process = require('process');
const baseURL = 'https://restcountries.eu/rest/v2';

const name = process.argv[2];

request.get(
    `${baseURL}/name/${name}`, 
    function(error, response, body) {
        if(error) {
            return console.log('讀取失敗', error);
        }

        let info = '';
        try {
            info = JSON.parse(body);
        } catch(error) {
            return console.log('讀取失敗', error);
        }

        if(response.statusCode === 404) {
            return console.log('找不到國家資訊');
        }

        for (let i = 0; i < info.length; i++) {
            console.log('============');
            console.log(`國家：${info[i].name}`);
            console.log(`首都：${info[i].capital}`);
            console.log(`貨幣：${info[i].currencies[0].code}`);
            console.log(`國碼：${info[i].callingCodes}`);
        }
    }
);