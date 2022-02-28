// getting-started.js
const mongoose = require("mongoose");
const productSchema = new mongoose.Schema({
    catalog_id: {
        type: Number,
        required: true,
        trim: true,
    },
    name: {
        type: String,
        required: true,
        uppercase: true,
    },
    price: {
        type: Number,
        default: 0,
        validate: {
            validator: function (value) {
                return (value > 0);
            },
            message: "Negative Price !",
        },

    },

    content: {
        type: String,
        default: "",
        trim: true,
    },

    image_link: {
        type: String,
        default: "",
        trim: true,
    },
    view: {
        type: Number,
        default: 0,
        trim: true,
    },
    slug: {
        type: String,
        required: true,
        trim: true,
    }
});
const product = mongoose.model("product", ProductsSchema, "product");
module.exports = product;
