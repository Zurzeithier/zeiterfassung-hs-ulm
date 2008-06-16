package database;

import application.ConfigDataProvider;
import java.sql.SQLException;
import java.util.LinkedList;
import java.util.Queue;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author manuel, steffen
 */
public class AdapterPool
{

    private static DBAdapter m_Adapter = null;
    private static Queue<DBAdapter> m_Adapters = new LinkedList<DBAdapter>();

    public synchronized static DBAdapter getDBAdapter()
    {
        if (m_Adapters.size() < ConfigDataProvider.getDBPoolSize())
        {
            try
            {
                DBAdapter newAdapter = new MSServerAdapter(ConfigDataProvider.getDBData().adress, ConfigDataProvider.getDBData().user, ConfigDataProvider.getDBData().password);
                m_Adapters.add(newAdapter);
            }
            catch (SQLException ex)
            {
                Logger.getLogger(AdapterPool.class.getName()).log(Level.SEVERE, null, ex);
            }
        }

        return m_Adapters.remove();
    }
    
    public synchronized static void releaseDBAdapter(DBAdapter adapter)
    {
        m_Adapters.add(adapter);
    }

}

