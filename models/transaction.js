// getting-started.js
const mongoose = require("mongoose");
const transactionSchema = new mongoose.Schema({
    user_id: {
        type: Number,
        required: true,
    },
    payment_info: {
        type: String,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },

    created_at: {
        type: Date,
        required: true,
    },
});
const transaction = mongoose.model("transaction", transactionsSchema, "transaction");
module.exports = transaction;