package database;

import pool.ObjectPool;
import application.ConfigDataProvider;
import pool.ObjectPoolException;
import java.sql.SQLException;

/**
 * Pool which handles database adapter objects
 * @author manuel, steffen
 */
public class DBAdapterObjectPool extends ObjectPool<DBAdapter>
{

    /**
     * Initializes the pool size
     * @param size
     */
    DBAdapterObjectPool(int size)
    {
        super(size);
    }

    /**
     * Creates new MSServerAdapter
     * @return New DBAdapter
     * @throws pool.ObjectPoolException
     */
    @Override
    protected DBAdapter createObject() throws ObjectPoolException
    {
        try
        {
            return new MSServerAdapter(ConfigDataProvider.getDBData().address, ConfigDataProvider.getDBData().user, ConfigDataProvider.getDBData().password);
        }
        catch (SQLException ex)
        {
            throw new ObjectPoolException(ex);
        }
    }

}
