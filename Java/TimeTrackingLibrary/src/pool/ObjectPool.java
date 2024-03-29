package pool;

import java.util.LinkedList;
import java.util.NoSuchElementException;
import java.util.Queue;

/**
 * Pool which contains adapter objects
 * @param <E> must extend the PoolObject Interfaces.
 * This promise the pool that the neccesary Methodes are implementet
 * @author manuel, steffen
 */
public abstract class ObjectPool<E extends PoolObject>
{

    /**
     * Active pool objects
     */
    private Queue<E> m_Active = new LinkedList<E>();
    
    /**
     * Idle pool objects
     */
    private Queue<E> m_Idle = new LinkedList<E>();
    
    /**
     * Maximum pool size
     */
    private int m_Size = 8;
    
    /**
     * Possible latency
     */
    private long m_WaitTime = 100000;

    /**
     * 
     * @param size
     */
    public ObjectPool(int size)
    {
        m_Size = size;
    }

    /**
     * Creates a new object for the pool (FactoryMethod).
     * @return created object
     * @throws ObjectPoolException 
     */
    abstract protected E createObject() throws ObjectPoolException;
     
    /**
     * Creates a new object for the pool while using createObject and calls init on the created Object.
     * If you need a new object use this function.
     * @return created object
     * @throws ObjectPoolException 
     */
    private E newObject() throws ObjectPoolException
    {
        E obj = createObject();
        obj.init();
        return obj;
    }

    /**
     * Obtains an instance from this pool. 
     * @return instance from this pool
     * @throws ObjectPoolException 
     */
    public synchronized E borrowObject() throws ObjectPoolException
    {
        // if size is 0 and max size is not reached create new object
        if (m_Idle.size() == 0 && m_Active.size() < m_Size)
        {
            m_Idle.add(newObject());
        }

        // if no object ist idle
        if (m_Idle.isEmpty())
        {
            try
            {
                wait(m_WaitTime);
                // if there is still no available object create a new one and delete
                // the latest active object
                if (m_Idle.isEmpty())
                {
                    m_Active.remove();  
                    m_Idle.add(newObject());
                }
            }
            catch (InterruptedException ex)
            {
                throw new ObjectPoolException(ex);
            }
        }

        try
        {
            E object = m_Idle.remove(); // throws exception if no element was found
            m_Active.add(object);

            return object;
        }
        catch (NoSuchElementException ex)
        {
            throw new ObjectPoolException(ex);
        }
    }

    /**
     * Return an instance to the pool.
     * @param object Instance which was borrowed from the pool
     * @throws ObjectPoolException 
     */
    public synchronized void returnObject(E object) throws ObjectPoolException
    {
        if (m_Active.remove(object))
        {
            if (object.validate())
            {
                m_Idle.add(object);
            }
            else
            {
                m_Idle.add(newObject());
            }
            notify();
        }
    }

    /**
     * Return the number of instances currently borrowed from this pool.
     * @return number of borrowed objects
     */
    public synchronized int getNumActive()
    {
        return m_Idle.size();
    }

    /**
     * Returns the number of idle objects in this pool.
     * @return number of idle objects
     */
    public synchronized int getNumIdle()
    {
        return m_Active.size();
    }

    /**
     * 
     * @return m_Size
     */
    public synchronized int size()
    {
        return m_Size;
    }

    /**
     * Sets the size of the pool.
     * @param size pool size
     */
    public synchronized void setSize(int size)
    {
        m_Size = size;
    }
    
    /**
     * Sets the maximum wait time for a thread to get an object.
     * @param time max wait time
     */
    public synchronized void setWaitTime(long time)
    {
        m_WaitTime = time;
    }

    /**
     * Clears all idle objects in this pool.
     */
    public void clear()
    {
        m_Idle.clear();
    }

}
