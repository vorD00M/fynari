export default function Footer() {
    return (
        <footer className="text-center text-sm text-gray-400 dark:text-gray-600 py-4 border-t">
            © {new Date().getFullYear()} Fylari CRM. All rights reserved.
        </footer>
    );
}
