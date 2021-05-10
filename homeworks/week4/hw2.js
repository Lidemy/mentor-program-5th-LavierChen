const request = require('request');
const process = require('process');
const baseURL = 'https://lidemy-book-store.herokuapp.com';

const action = process.argv[2];
const para1 = process.argv[3];
const para2 = process.argv[4];

switch(action) {
    case 'list':
        bookList();
        break;
    case 'read':
        bookGet(para1);
        break;
    case 'delete':
        bookDelete(para1);
        break;
    case 'create':
        bookCreate(para1);
        break;
    case 'update':
        bookUpdate(para1, para2);
        break;
    default:
        console.log('Available commands: list, read, delete, create and update');
}

function bookList() {
    request.get(
        `${baseURL}/books?_limit=20`, 
        function (error, response, body) {
            if(error) {
                return console.log('讀取失敗', error);
            }

            let books = '';
            try {
                books = JSON.parse(body);
            } catch(error) {
                return console.log('讀取失敗', error);
            }
            
            for(let i = 0; i < books.length; i++) {
                console.log(`${books[i].id} ${books[i].name}`);
            }
        }
    );
}

function bookGet(id) {
    request.get(
        `${baseURL}/books/${id}`,
        function(error, response, body) {
            if(error) {
                return console.log('讀取失敗', error);
            }

            const book = JSON.parse(body);
            console.log(`${book.id} ${book.name}`);
        }
    );
}

function bookDelete(id) {
    request.delete(
        `${baseURL}/books/${id}`,
        function(error, response, body) {
            if(error) {
                return console.log('刪除失敗', error);
            }
            console.log(`刪除 id 為 ${id} 的書籍`);
        }
    );
}

function bookCreate(bookname) {
    request.post(
        {
            url: `${baseURL}/books`,
            form: {
                name:`${bookname}`
            }
        },
        function(error, response, body) {
            if(error) {
                return console.log('新增失敗', error);
            }
            console.log(`新增一本名為 ${bookname} 的書籍`);
        }
    );
}

function bookUpdate(id, newname) {
    request.patch(
        {
            url: `${baseURL}/books/${id}`,
            form: {
                name:`${newname}`
            }
        },
        function(error, response, body) {
            if(error) {
                return console.log('更新失敗', error);
            }
            console.log(`更新 id 為 ${id} 的書名為 ${newname}`);
        }
    );
}