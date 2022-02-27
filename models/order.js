// getting-started.js
const mongoose = require("mongoose");
const orderSchema = new mongoose.Schema({
    transaction_id: {
        type: Number,
        required: true,
    },    
    product_id: {
        type: Number,
        required: true,
    },
    quantity: {
        type: Number,
        default: 1,
        required: true,
    },
    status: {
        type: Number,
        default: 0,
        required: true,
    },
});
const order = mongoose.model("order", ordersSchema, "order");
module.exports = order;