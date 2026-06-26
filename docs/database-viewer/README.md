# Database Viewer

A simple development-only database viewer for local debugging and inspection.

## Access

Open the viewer at:

- /dev/database-viewer

The page is only available when the application environment is one of:

- local
- testing
- development

## What it shows

- A list of database tables
- Row counts for each table
- Column names for the selected table
- The first 25 rows of the selected table

## Notes

- This feature is read-only.
- It is intended for development and troubleshooting only.
- It should not be enabled in production.
