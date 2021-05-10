const request = require('request');

request(
    {
        method: 'GET',
        url: 'https://api.twitch.tv/kraken/games/top',
        headers: {
            'Client-ID': '8fe9i00c0swhagvo69nqqz3uzensca',
            Accept: 'application/vnd.twitchtv.v5+json',
        }
    },
    function(error, response, body) {
        if(error) {
            return console.log(error);
        }

        let info = '';
        try {
            info = JSON.parse(body);
        } catch (error) {
            return console.log(error);
        }
        
        let topGame = info.top;
        for(let i = 0; i < topGame.length; i++) {
            console.log(`${topGame[i].viewers} ${topGame[i].game.name}`);
        }
    }
);