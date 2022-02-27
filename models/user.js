// getting-started.js
const mongoose = require("mongoose");
const userSchema = new mongoose.Schema({
    name: {
        type: String,
        required: true
    },
    email: {
        type: String,
        required: true,
    },
    phone: {
        type: Number,
        required: true
    },
    address: {
        type: String,
        required: true,
    },
    user_name: {
        type: Number,
        required: true,
        trim: true,
        validate: {
            validator: function (value) {
                return (value != null);
            },
            message: "No username input",
        },
    },
    password: {
        type: String,
        required: true,
        trim: true,
        validate: {
            validator: function (value) {
                return (value != null);
            },
            message: "No password input",
        },
    },
});
const user = mongoose.model("user", usersSchema, "user");
module.exports = user;