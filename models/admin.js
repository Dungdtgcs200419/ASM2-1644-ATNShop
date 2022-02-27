// getting-started.js
const mongoose = require("mongoose");
const adminSchema = new mongoose.Schema({
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
    status_admin: {
        type: Number,
        default: 0,
        required: true,
    },

    created_at: {
        type: Date,
        required: true,
    },
});
const admin = mongoose.model("admin", adminsSchema, "admin");
module.exports = admin;