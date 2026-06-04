# Frontend Notes

This folder contains the Svelte/Vite source for the webstore frontend.

For full project setup, use the main README in the project root:

```text
../README.md
```

## Install Frontend Packages

From this folder:

```powershell
npm install
```

## Build Frontend Assets

From this folder:

```powershell
npm run build
```

The build writes files here:

```text
frontend/public/assets/index.css
frontend/public/assets/index.js
```

CodeIgniter loads those files from PHP views.

## Development Server

From this folder:

```powershell
npm run dev
```

The dev server runs on:

```text
http://localhost:3000
```

The main CodeIgniter app still runs separately, usually on:

```text
http://localhost:1010
```
