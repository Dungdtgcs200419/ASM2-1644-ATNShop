// getting-started.js
const mongoose = require("mongoose");
const catalogSchema = new mongoose.Schema({
    name: {
        type: String,
        required: true,
    },
});
const catalog = mongoose.model("catalog", catalogsSchema, "catalog");
module.exports = catalog;