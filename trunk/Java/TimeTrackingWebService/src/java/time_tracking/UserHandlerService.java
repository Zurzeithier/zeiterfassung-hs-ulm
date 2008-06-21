/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package time_tracking;

import beans.UserBean;
import exceptions.DBException;
import handlers.UserHandler;
import javax.jws.WebMethod;
import javax.jws.WebParam;
import javax.jws.WebService;

/**
 *
 * @author manuel
 */
@WebService()
public class UserHandlerService
{

    /**
     * Web service operation
     * @param username 
     * @param password 
     * @return
     * @throws DBException 
     */
    @WebMethod(operationName = "loginUser")
    public UserBean loginUser(@WebParam(name = "username") String username, @WebParam(name = "password") String password) throws DBException
    {
        return UserHandler.loginUser(username, password);
    }

    /**
     * Web service operation
     * @param mid
     * @param bean
     * @return
     * @throws DBException 
     */
    @WebMethod(operationName = "changeUser")
    public boolean changeUser(@WebParam(name = "mid") int mid, @WebParam(name = "userbean") UserBean bean) throws DBException
    {
        return UserHandler.changeUser(mid, bean);
    }

    /**
     * Web service operation
     * @param username
     * @return
     * @throws DBException 
     */
    @WebMethod(operationName = "sendNewPassword")
    public boolean sendNewPassword(@WebParam(name = "username") String username) throws DBException
    {
        return UserHandler.sendNewPassword(username);
    }

}
