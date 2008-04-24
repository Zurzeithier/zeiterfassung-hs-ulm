using System;
using System.Collections.Generic;
using System.Data.SqlClient;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Zeiterfassung.NET
{
    class Database_connector
    {
        SqlConnection con;
        public void Subscribe(LogIn Connection)
        {
            Connection.LogInClickedHandlerEvent += new LogIn.LogInClickedHandler(CreateConnection);
        }

        private void CreateConnection(string[] args)
        {
            string ConnectionString = "Data Source=" + args[0] + ";" + "Initial Catalog=" + args[3] + ";" + "User ID=" + args[1] + ";" + "Password=" + args[2];
            con = new SqlConnection(ConnectionString);
            con.Open();

            con.CreateCommand();

        }
    }
}
