const request = require('request');
const baseURL = 'https://lidemy-book-store.herokuapp.com';

request.get(
    `${baseURL}/books?_limit=10`, 
    function (error, response, body) {
        if(error){
            return console.log(error);
        }

        let books = '';
        try{
            books = JSON.parse(body);
        } catch(error) {
            return console.log(error);
        }

        for(let i = 0; i < books.length; i++) {
            console.log(`${books[i].id} ${books[i].name}`);
        }
    }
);