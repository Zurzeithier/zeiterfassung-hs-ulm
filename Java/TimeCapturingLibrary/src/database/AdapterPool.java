package database;

import application.ConfigDataProvider;

/**
 *
 * @author manuel, steffen
 */
public class AdapterPool
{

    private static DBAdapter m_Adapter = null;

    public static DBAdapter getDBAdapter()
    {
        if (m_Adapter == null)
        {
            m_Adapter = new MSServerAdapter(
                    ConfigDataProvider.getDBData().adress,
                    ConfigDataProvider.getDBData().user,
                    ConfigDataProvider.getDBData().password);
        }

        return m_Adapter;
    }

}

