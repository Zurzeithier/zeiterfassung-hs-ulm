using System;
using System.Collections.Generic;
using System.Linq;
using System.Windows.Forms;

namespace Zeiterfassung.NET
{
    static class Program
    {
        /// <summary>
        /// The main entry point for the application.
        /// </summary>
        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);

            LogIn MainForm = new LogIn();
            Database_connector db = new Database_connector();
            db.Subscribe(MainForm);

            //Application.Run(new LogIn());
            Application.Run(MainForm);
        }
    }
}
