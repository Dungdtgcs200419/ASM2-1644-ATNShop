const mongoose = require('mongoose');

const USER = 'useradmin';
const PASSWORD = 'connectMongoDB';
const DB_NAME = 'TestATN';

async function connect() {
  try {
    await mongoose.connect(
      'mongodb+srv://' +
        USER +
        ':' +
        PASSWORD +
        '@cluster0.8zaaw.mongodb.net/' +
        DB_NAME +
        '?retryWrites=true&w=majority'
    );
    console.log('Connect succesfully!!');
  } catch (error) {
    console.log('Connect failure');
  }
}

module.exports = { connect };
