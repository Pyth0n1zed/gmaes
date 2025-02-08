const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
const path = require('path');
const fs = require('fs');

const app = express();
const port = process.env.PORT || 3000;

// Enable CORS for your GitHub Pages site
app.use(cors({ origin: 'https://your-github-username.github.io' }));

// Parse incoming JSON requests
app.use(express.json());

// Connect to MongoDB Atlas
const mongoURI = process.env.MONGO_URI;  // Your MongoDB connection string from Render
mongoose.connect(mongoURI, { useNewUrlParser: true, useUnifiedTopology: true })
  .then(() => console.log('Connected to MongoDB'))
  .catch(err => console.error('MongoDB connection error:', err));

// Define ProductKey schema and model
const productKeySchema = new mongoose.Schema({
  key: String,
  fileName: String // The file associated with the product key
});

const ProductKey = mongoose.model('ProductKey', productKeySchema);

// Endpoint to validate product key and send file
app.post('/validate-key', async (req, res) => {
  const { productKey } = req.body;

  try {
    // Find the product key in the database
    const key = await ProductKey.findOne({ key: productKey });

    if (key) {
      // If the key is valid, send the file associated with it
      const filePath = path.join(__dirname, 'files', key.fileName); // Assuming your files are in a "files" folder
      
      if (fs.existsSync(filePath)) {
        res.download(filePath);  // Download the file
      } else {
        res.status(404).json({ message: 'File not found' });
      }
    } else {
      // If the product key is invalid
      res.status(404).json({ message: 'Invalid product key' });
    }
  } catch (error) {
    console.error('Error validating product key:', error);
    res.status(500).json({ message: 'Server error' });
  }
});

// Start the server
app.listen(port, () => {
  console.log(`Server running on port ${port}`);
});
