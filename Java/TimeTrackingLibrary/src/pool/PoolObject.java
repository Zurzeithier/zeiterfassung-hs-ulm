package pool;

/**
 *
 * @author manuel, steffen
 */
public interface PoolObject 
{
    
    /**
     * Is called when the object was created from the pool.
     * @throws pool.ObjectPoolException - Thrown when something went wrong
     */
    public void init() throws ObjectPoolException;
    
    /**
     * Tests if the object is still in a valid state.
     * @return true is stat is valid, otherwise false
     */
    public boolean validate();
    
    /**
     * Is called when the pool destroys the object.
     * @throws pool.ObjectPoolException - Thrown when something went wrong
     */
    public void destroy() throws ObjectPoolException;
}
